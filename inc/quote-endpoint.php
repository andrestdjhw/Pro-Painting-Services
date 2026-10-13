<?php
/**
 * POST /wp-json/pps/v1/quote — receives the React quote form.
 *
 * Mail goes through wp_mail(); install an SMTP plugin (e.g. WP Mail SMTP)
 * so it is delivered reliably. The form only shows success when wp_mail()
 * actually accepted the message.
 *
 * Spam protection: honeypot field, minimum fill time, per-IP rate limit.
 */

function pps_register_quote_route() {
  register_rest_route('pps/v1', '/quote', array(
    'methods'             => 'POST',
    'callback'            => 'pps_handle_quote',
    'permission_callback' => '__return_true',
  ));
}
add_action('rest_api_init', 'pps_register_quote_route');

function pps_handle_quote(WP_REST_Request $request) {
  $p = $request->get_json_params() ?: $request->get_body_params();

  // Honeypot + timing: bots fill hidden fields and submit instantly. Pretend success.
  $elapsed = time() - (int) ($p['started_at'] ?? 0) / 1000;
  if (!empty($p['website']) || $elapsed < 3) {
    return new WP_REST_Response(array('ok' => true), 200);
  }

  $ip_key = 'pps_quote_' . md5($_SERVER['REMOTE_ADDR'] ?? '');
  $count  = (int) get_transient($ip_key);
  if ($count >= 5) {
    return pps_quote_error('Too many requests. Please call us instead.', 429);
  }

  $types    = pps_property_types();
  $services = pps_services();
  $data     = array(
    'name'          => sanitize_text_field($p['name'] ?? ''),
    'company'       => sanitize_text_field($p['company'] ?? ''),
    'email'         => sanitize_email($p['email'] ?? ''),
    'phone'         => sanitize_text_field($p['phone'] ?? ''),
    'location'      => sanitize_text_field($p['location'] ?? ''),
    'property_type' => $types[$p['property_type'] ?? ''] ?? '',
    'service'       => isset($services[$p['service'] ?? '']) ? $services[$p['service']]['name'] : (($p['service'] ?? '') === 'other' ? 'Not sure / Other' : ''),
    'details'       => sanitize_textarea_field($p['details'] ?? ''),
    'consent'       => !empty($p['consent']),
    'page'          => esc_url_raw($p['page'] ?? ''),
  );

  $errors = array();
  if (!$data['name']) $errors['name'] = 'Please enter your name.';
  if (!is_email($data['email'])) $errors['email'] = 'Please enter a valid email.';
  if (strlen(preg_replace('/\D/', '', $data['phone'])) < 10) $errors['phone'] = 'Please enter a valid phone number.';
  if (!$data['location']) $errors['location'] = 'Please enter the project location.';
  if (!$data['details']) $errors['details'] = 'Please describe the painting work.';
  if (!$data['consent']) $errors['consent'] = 'Please agree to be contacted.';
  if ($errors) {
    return new WP_REST_Response(array('ok' => false, 'errors' => $errors), 422);
  }

  $biz  = pps_business();
  $rows = array(
    'Name'             => $data['name'],
    'Company/Property' => $data['company'],
    'Email'            => $data['email'],
    'Phone'            => $data['phone'],
    'Project Location' => $data['location'],
    'Property Type'    => $data['property_type'],
    'Service Needed'   => $data['service'],
    'Project Details'  => $data['details'],
    'Sent from'        => $data['page'],
  );
  $body = '';
  foreach (array_filter($rows) as $label => $value) {
    $body .= "{$label}: {$value}\n";
  }

  $subject = 'New quote request: ' . ($data['service'] ?: 'Painting project') . " — {$data['name']}";
  $sent    = wp_mail($biz['leads_to'], $subject, $body, array("Reply-To: {$data['name']} <{$data['email']}>"));

  if (!$sent) {
    return pps_quote_error("We couldn't send your request. Please call {$biz['phone']} or email {$biz['email']}.", 500);
  }

  set_transient($ip_key, $count + 1, 10 * MINUTE_IN_SECONDS);

  // Receipt for the requester.
  wp_mail(
    $data['email'],
    'We received your request — Pro Painting Services',
    "Hi {$data['name']},\n\nThank you. Your request has been sent to Pro Painting Services. Our team will review it and contact you to discuss next steps.\n\nFor anything else, call {$biz['phone']} or reply to {$biz['email']}.\n\n— Pro Painting Services",
    array("Reply-To: Pro Painting Services <{$biz['email']}>")
  );

  return new WP_REST_Response(array('ok' => true), 200);
}

function pps_quote_error($message, $status) {
  return new WP_REST_Response(array('ok' => false, 'message' => $message), $status);
}
