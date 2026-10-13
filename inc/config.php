<?php
/**
 * Central business data and page copy.
 *
 * Everything that appears in more than one place (NAP, services, dropdown
 * options) lives here so templates, React props and structured data stay in
 * sync. Copy comes from the 828 PPS brief (Oct 2026).
 *
 * Leave a value empty when it has not been verified yet: sections that depend
 * on it (reviews, Google Business link, socials) are hidden automatically.
 */

function pps_business() {
  return array(
    'name'          => 'Pro Painting Services',
    'phone'         => '(505) 582-7126',
    'phone_href'    => 'tel:+15055827126',
    'email'         => 'office@propaintingsrvc.com',
    'street'        => '3136 Coors Blvd NW',
    'city'          => 'Albuquerque',
    'region'        => 'NM',
    'postal'        => '87120',
    'service_area'  => 'Serving New Mexico. Select out-of-state project inquiries are welcome.',
    // TODO(verify): official Google Business Profile URL and reviews URL.
    'gbp_url'       => '',
    'reviews_url'   => '',
    // TODO(verify): exact social URLs. Only verified profiles should be listed.
    'socials'       => array(
      // 'Instagram' => 'https://www.instagram.com/propainting.services/',
    ),
    // Inbox that receives quote requests.
    'leads_to'      => 'office@propaintingsrvc.com',
  );
}

/**
 * Approved Google review excerpts. Keep empty until real, approved reviews
 * exist — the Home section is not rendered without them.
 * Format: array('quote' => '', 'author' => '', 'rating' => 5|null)
 */
function pps_reviews() {
  return array();
}

function pps_property_types() {
  return array(
    'commercial'  => 'Commercial',
    'multifamily' => 'Multifamily / Apartments',
    'hospitality' => 'Hospitality / Hotel',
    'office'      => 'Office',
    'other'       => 'Other',
  );
}

/**
 * The 9 service landing pages, children of /services/.
 * Keys are the page slugs.
 */
function pps_services() {
  $final_cta = array(
    'heading' => 'Tell Us About Your Property.',
    'text'    => 'Share your location, project type and the painting work you need. Our team can review your request and contact you to discuss next steps.',
  );

  $services = array(
    'commercial-exterior-painting' => array(
      'name'      => 'Commercial Exterior Painting',
      'summary'   => 'Exterior painting for retail and commercial buildings.',
      'teaser'    => 'Exterior painting for retail buildings and commercial facilities across New Mexico.',
      'group'     => 'commercial',
      'seo_title' => 'Commercial Exterior Painting | Albuquerque NM | PPS',
      'meta'      => 'Explore commercial exterior painting for business properties throughout New Mexico. Contact Pro Painting Services to discuss your building.',
      'eyebrow'   => 'Commercial Painting',
      'h1'        => 'Exterior Painting for Commercial Buildings.',
      'intro'     => 'Exterior painting for business properties and commercial facilities in New Mexico. Share your building type, location and scope so we can discuss the work.',
      'fit'       => 'Retail and commercial facilities have their own exterior surfaces, customer access patterns and maintenance priorities. This service is intended for commercial building painting projects.',
      'tags'      => array('Retail', 'Commercial Facilities', 'Business Properties'),
      'scope'     => array(
        array('title' => 'Building & Size', 'text' => 'Building type and approximate size.'),
        array('title' => 'Surface Condition', 'text' => 'Current exterior surface condition.'),
        array('title' => 'Access & Timing', 'text' => 'Access considerations and desired project timing.'),
      ),
      'planning'  => 'Start with the property address, number of buildings and images of the existing surfaces. We can then discuss the scope and next steps.',
      'faq'       => array(
        array('q' => 'Do you paint retail and other commercial buildings?', 'a' => 'Commercial exteriors are one of our listed services. Share the type of property and the surfaces involved.'),
        array('q' => 'What should I send when asking for a quote?', 'a' => 'Location, approximate building size, photos and a description of the painting work.'),
      ),
    ),
    'multifamily-exterior-painting' => array(
      'name'      => 'Multifamily Exterior Painting',
      'summary'   => 'Exterior painting for apartment communities and multifamily structures.',
      'teaser'    => 'Exterior building painting for apartment communities, from one building to several.',
      'group'     => 'multifamily',
      'seo_title' => 'Multifamily Exterior Painting in New Mexico | PPS',
      'meta'      => 'Exterior painting for multifamily and apartment communities in New Mexico. Discuss building counts, surfaces and your project with PPS.',
      'eyebrow'   => 'Multifamily Painting',
      'h1'        => 'Exterior Painting for Apartment Communities.',
      'intro'     => 'Exterior building painting for apartments and multifamily properties, with project discussions shaped around your community’s needs.',
      'fit'       => 'For property managers and owners evaluating exterior painting across one building or multiple structures.',
      'tags'      => array('Apartment Communities', 'Multifamily', 'Managed Housing'),
      'scope'     => array(
        array('title' => 'Property & Buildings', 'text' => 'Property name, location and building count.'),
        array('title' => 'Surfaces & Areas', 'text' => 'Surfaces and areas to be painted.'),
        array('title' => 'Access & Scheduling', 'text' => 'Access, occupancy and scheduling considerations.'),
      ),
      'planning'  => 'Share how many buildings are involved, the exterior areas and any community-specific restrictions. Our team can review the request and discuss the project.',
      'faq'       => array(
        array('q' => 'Can I inquire about multiple buildings?', 'a' => 'Yes. Include the approximate number of buildings and the community location.'),
        array('q' => 'Do you work with property management teams?', 'a' => 'Multifamily exterior painting is a core listed service; property contacts can send details for discussion.'),
      ),
    ),
    'interior-unit-painting' => array(
      'name'      => 'Interior Unit Painting',
      'summary'   => 'Painting for interiors of apartment units and managed housing.',
      'teaser'    => 'Interior painting for apartment units, whether one unit or a group of units.',
      'group'     => 'multifamily',
      'seo_title' => 'Interior Unit Painting for Apartments | PPS New Mexico',
      'meta'      => 'Apartment unit interior painting for managed properties in New Mexico. Contact Pro Painting Services about your units and project scope.',
      'eyebrow'   => 'Apartment Unit Painting',
      'h1'        => 'Interior Painting for Apartment Units.',
      'intro'     => 'Painting for interior apartment units and managed residential spaces. Tell us about your unit count and the rooms or surfaces involved.',
      'fit'       => 'For apartment and property teams planning painting for individual units or groups of units.',
      'tags'      => array('Apartment Units', 'Managed Housing', 'Multifamily'),
      'scope'     => array(
        array('title' => 'Units & Rooms', 'text' => 'Number of units and room types.'),
        array('title' => 'Surfaces Included', 'text' => 'Surfaces and areas included.'),
        array('title' => 'Location & Coordination', 'text' => 'Property location and desired coordination details.'),
      ),
      'planning'  => 'Provide an overview of the units, approximate scope and any property requirements. We can discuss next steps without asking you to explain everything from scratch.',
      'faq'       => array(
        array('q' => 'Can I ask about painting several units?', 'a' => 'Yes. Include the number of units and property location.'),
        array('q' => 'What information helps with an inquiry?', 'a' => 'Rooms to be painted, surface photos where available, and a description of the intended work.'),
      ),
    ),
    'hotel-painting' => array(
      'name'      => 'Hotel Painting',
      'summary'   => 'Interior and exterior painting for hotels.',
      'teaser'    => 'Interior and exterior painting for hotels and other hospitality properties.',
      'group'     => 'hospitality',
      'seo_title' => 'Hotel Interior & Exterior Painting | New Mexico PPS',
      'meta'      => 'Commercial painting for hotel interiors and exteriors in New Mexico. Discuss your hospitality property’s painting needs with PPS.',
      'eyebrow'   => 'Hospitality Painting',
      'h1'        => 'Interior & Exterior Painting for Hotels.',
      'intro'     => 'Painting for hotel interiors and exterior areas, with project planning conversations tailored to the property and scope.',
      'fit'       => 'For hotel owners, hospitality managers and property teams exploring work on public-facing or interior surfaces.',
      'tags'      => array('Hotels', 'Hospitality', 'Public-Facing Areas'),
      'scope'     => array(
        array('title' => 'Areas & Surfaces', 'text' => 'Property areas and surface types.'),
        array('title' => 'Site Operations', 'text' => 'Site access or operational considerations.'),
        array('title' => 'Location & Scope', 'text' => 'Project location, images and desired scope.'),
      ),
      'planning'  => 'Tell us which areas need painting and whether the project covers interior spaces, exterior elevations or both.',
      'faq'       => array(
        array('q' => 'Can you discuss both interior and exterior hotel work?', 'a' => 'Both are listed as part of our hotel painting services.'),
        array('q' => 'What should a hospitality property share?', 'a' => 'Location, surfaces, photos and any access considerations relevant to the request.'),
      ),
    ),
    'office-renovations' => array(
      'name'      => 'Commercial & Apartment Office Renovations',
      'nav_name'  => 'Office Renovations',
      'summary'   => 'Selected commercial and apartment office renovation projects.',
      'teaser'    => 'Select renovation and improvement work for commercial and apartment office spaces.',
      'group'     => 'commercial',
      'seo_title' => 'Commercial & Apartment Office Renovations | PPS',
      'meta'      => 'Discuss commercial and apartment office renovation projects in New Mexico with Pro Painting Services. Share your property and proposed scope.',
      'eyebrow'   => 'Property Improvement',
      'h1'        => 'Commercial & Apartment Office Renovations.',
      'intro'     => 'Select renovation and improvement work for commercial and apartment office spaces. Tell us about the type of space and planned work.',
      'fit'       => 'For commercial office and apartment community office spaces needing discussion of a specific renovation scope.',
      'tags'      => array('Commercial Offices', 'Leasing Offices', 'Apartment Communities'),
      'scope'     => array(
        array('title' => 'Space & Outcome', 'text' => 'Existing space and desired outcome.'),
        array('title' => 'Work & References', 'text' => 'Work description and reference photos.'),
        array('title' => 'Site & Constraints', 'text' => 'Site location and project constraints.'),
      ),
      'planning'  => 'Tell us what needs to change, where the work is located and what supporting images you have. Our team can clarify whether the scope fits the services provided.',
      'faq'       => array(
        array('q' => 'Do you renovate apartment leasing offices?', 'a' => 'Commercial and apartment office remodels and renovations are part of our listed services; provide your space and scope for confirmation.'),
        array('q' => 'Can you handle specialized structural or mechanical work?', 'a' => 'Please describe the request so the team can confirm the actual scope; those capabilities are not specified here.'),
      ),
    ),
    'pool-area-painting' => array(
      'name'      => 'Pool Area Painting',
      'summary'   => 'Painting for pool-related property surfaces.',
      'teaser'    => 'Painting for pool-related property surfaces, reviewed individually for each project.',
      'group'     => 'specialty',
      'seo_title' => 'Pool Area Painting Services | PPS New Mexico',
      'meta'      => 'Contact PPS about painting needs for pool-related property areas in New Mexico. Surface suitability and project scope are reviewed individually.',
      'eyebrow'   => 'Specialty Painting',
      'h1'        => 'Painting for Pool-Related Property Areas.',
      'intro'     => 'Painting work for pool-related areas and surfaces as part of property improvement needs. Tell us exactly which surfaces require painting.',
      'fit'       => 'For managed properties seeking painting on appropriate pool-area surfaces; specific substrates and coatings must be confirmed before quoting.',
      'tags'      => array('Apartment Communities', 'Hotels', 'Managed Properties'),
      'scope'     => array(
        array('title' => 'Exact Surface', 'text' => 'Identify the exact surface to paint.'),
        array('title' => 'Photos & Condition', 'text' => 'Supply clear current photos and condition.'),
        array('title' => 'Location & Access', 'text' => 'Note location and access requirements.'),
      ),
      'planning'  => 'Because pool-related surfaces vary, our team needs to understand the specific scope before discussing possible options.',
      'faq'       => array(
        array('q' => 'Do you construct or repair swimming pools?', 'a' => 'This page covers painting only, not construction or repair services.'),
        array('q' => 'Can all pool surfaces be painted?', 'a' => 'Suitability depends on the surface and scope; please send details for review.'),
      ),
    ),
    'parking-area-painting' => array(
      'name'      => 'Parking Area Painting',
      'summary'   => 'Painting for parking-associated property surfaces.',
      'teaser'    => 'Painting for parking-related property surfaces, based on your specific scope.',
      'group'     => 'specialty',
      'seo_title' => 'Parking Area Painting | PPS New Mexico',
      'meta'      => 'Discuss painting needs for parking-related property areas in New Mexico. Share surface details and photos with PPS.',
      'eyebrow'   => 'Specialty Painting',
      'h1'        => 'Painting for Parking-Related Areas.',
      'intro'     => 'Painting for parking-associated areas and property surfaces, based on the specific scope described by the client.',
      'fit'       => 'For commercial and managed properties seeking painting work on parking-related surfaces.',
      'tags'      => array('Commercial', 'Multifamily', 'Managed Properties'),
      'scope'     => array(
        array('title' => 'Area & Surface', 'text' => 'Area and surface to be painted.'),
        array('title' => 'Photos & Dimensions', 'text' => 'Current site photos and dimensions.'),
        array('title' => 'Access & Use', 'text' => 'Access and use requirements.'),
      ),
      'planning'  => 'Tell us which parking-related surfaces need work. We will clarify the project scope before discussing next steps.',
      'faq'       => array(
        array('q' => 'Do you provide asphalt paving?', 'a' => 'This page is for painting; paving is not a confirmed service.'),
        array('q' => 'Do you perform parking lot striping?', 'a' => 'Describe your request so PPS can clarify availability.'),
      ),
    ),
    'metal-painting' => array(
      'name'      => 'Metal Painting',
      'summary'   => 'Painting for metal elements on commercial properties.',
      'teaser'    => 'Painting for metal elements and surfaces on commercial and managed properties.',
      'group'     => 'specialty',
      'seo_title' => 'Metal Painting for Commercial Properties | PPS',
      'meta'      => 'Painting for metal surfaces at commercial and managed properties. Discuss the material and project scope with PPS in New Mexico.',
      'eyebrow'   => 'Specialty Painting',
      'h1'        => 'Painting for Metal Property Surfaces.',
      'intro'     => 'Painting for metal elements and surfaces on managed and commercial properties, subject to the specific material and project scope.',
      'fit'       => 'For property operators looking to address metal surface painting within a property.',
      'tags'      => array('Commercial', 'Multifamily', 'Hospitality'),
      'scope'     => array(
        array('title' => 'Metal & Condition', 'text' => 'Type of metal and finish condition.'),
        array('title' => 'Location & Size', 'text' => 'Location, size and accessibility.'),
        array('title' => 'Photos & Outcome', 'text' => 'Photos and intended outcome.'),
      ),
      'planning'  => 'Provide photos and substrate details so PPS can evaluate whether your request fits its painting capabilities.',
      'faq'       => array(
        array('q' => 'Do you fabricate metal structures?', 'a' => 'Metal fabrication is not a listed service.'),
        array('q' => 'Can any metal be coated?', 'a' => 'The team must review the metal type and intended painting scope before advising.'),
      ),
    ),
    'signage-painting' => array(
      'name'      => 'Signage Painting',
      'summary'   => 'Painting related to existing signage surfaces.',
      'teaser'    => 'Painting for existing signage surfaces, subject to the sign type and location.',
      'group'     => 'specialty',
      'seo_title' => 'Signage Painting Services | New Mexico PPS',
      'meta'      => 'Discuss painting for existing commercial signage surfaces with Pro Painting Services. Send project photos and location for scope review.',
      'eyebrow'   => 'Specialty Painting',
      'h1'        => 'Painting for Existing Signage Surfaces.',
      'intro'     => 'Painting work relating to existing signage surfaces, subject to the sign type and location.',
      'fit'       => 'For commercial properties with signage surfaces that may need painting. This does not imply sign design, fabrication or installation.',
      'tags'      => array('Retail', 'Commercial Properties', 'Hospitality'),
      'scope'     => array(
        array('title' => 'Material & Size', 'text' => 'Sign material and approximate dimensions.'),
        array('title' => 'Access & Condition', 'text' => 'Access requirements and current condition.'),
        array('title' => 'Photos & Location', 'text' => 'Photos and property location.'),
      ),
      'planning'  => 'Describe the existing sign and the requested painting work. Our team can clarify the scope and availability.',
      'faq'       => array(
        array('q' => 'Do you manufacture or install signs?', 'a' => 'This page describes signage painting only, not fabrication or installation.'),
        array('q' => 'Can you paint any type of sign?', 'a' => 'Please provide photos and materials so the scope can be reviewed.'),
      ),
    ),
  );

  foreach ($services as $slug => &$service) {
    $service['slug']      = $slug;
    $service['nav_name']  = $service['nav_name'] ?? $service['name'];
    $service['final_cta'] = $final_cta;
  }

  return $services;
}

function pps_service($slug) {
  $services = pps_services();
  return $services[$slug] ?? null;
}

function pps_service_url($slug) {
  return home_url("/services/{$slug}/");
}

/** Project portfolio filters (Projects page). */
function pps_project_categories() {
  return array(
    'commercial'  => 'Commercial',
    'multifamily' => 'Multifamily',
    'hospitality' => 'Hospitality',
    'specialty'   => 'Specialty',
  );
}
