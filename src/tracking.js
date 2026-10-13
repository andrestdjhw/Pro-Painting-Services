/**
 * GA4 / GTM lead events pushed to the dataLayer.
 * Events: click_to_call, click_email, service_quote_click, form_start,
 * form_submit_success, view_project.
 */
export function track(event, params = {}) {
  window.dataLayer = window.dataLayer || []
  window.dataLayer.push({ event, ...params })
}

export function initClickTracking() {
  document.addEventListener("click", e => {
    const link = e.target.closest("a")
    if (!link) return
    const href = link.getAttribute("href") || ""

    if (href.startsWith("tel:")) track("click_to_call", { link_url: href })
    else if (href.startsWith("mailto:")) track("click_email", { link_url: href })

    if (link.dataset.track) track(link.dataset.track, { link_url: link.href, link_text: link.textContent.trim() })
    if (link.closest("[data-project]")) track("view_project", { project: link.closest("[data-project]").dataset.project })
  })
}
