import React, { useEffect, useState } from "react"

/**
 * Cookie consent banner wired to Google Consent Mode v2.
 * Defaults (all denied) are set in <head> by inc/setup.php → pps_consent_defaults(),
 * so GA4 / GTM added later only use cookies after the visitor accepts.
 * The choice is stored in localStorage under STORAGE_KEY ("granted" | "denied").
 * Any element with [data-cookie-settings] reopens the banner (footer link).
 */
export const STORAGE_KEY = "pps_cookie_consent"

function readChoice() {
  try {
    return window.localStorage.getItem(STORAGE_KEY)
  } catch {
    return null
  }
}

function saveChoice(value) {
  try {
    window.localStorage.setItem(STORAGE_KEY, value)
  } catch {
    // Private mode / blocked storage: the banner simply shows again next visit
  }
}

function applyConsent(value) {
  const state = value === "granted" ? "granted" : "denied"
  window.dataLayer = window.dataLayer || []
  if (typeof window.gtag === "function") {
    window.gtag("consent", "update", {
      analytics_storage: state,
      ad_storage: state,
      ad_user_data: state,
      ad_personalization: state
    })
  }
  window.dataLayer.push({ event: "cookie_consent_update", consent: state })
}

export default function CookieBanner({ data }) {
  const [open, setOpen] = useState(false)

  useEffect(() => {
    if (!readChoice()) setOpen(true)
    const reopen = e => {
      if (!e.target.closest("[data-cookie-settings]")) return
      e.preventDefault()
      setOpen(true)
    }
    document.addEventListener("click", reopen)
    return () => document.removeEventListener("click", reopen)
  }, [])

  if (!open) return null

  const choose = value => {
    saveChoice(value)
    applyConsent(value)
    setOpen(false)
  }

  return (
    <div
      role="dialog"
      aria-live="polite"
      aria-label="Cookie consent"
      className="pps-cookie fixed inset-x-3 bottom-[4.25rem] z-[60] max-w-md border-t-2 border-brand bg-ink p-5 text-sm text-steel-300 shadow-2xl sm:inset-x-auto sm:left-4 lg:bottom-4"
    >
      <p className="font-display text-base font-bold text-white">We use cookies</p>
      <p className="mt-2 leading-relaxed">
        We use cookies to understand how visitors use our website and to improve your experience. You can accept or decline
        non-essential cookies. See our{" "}
        <a href={data.privacyUrl} className="font-semibold text-white underline hover:text-steel-300">
          Privacy Policy
        </a>
        .
      </p>
      <div className="mt-4 flex flex-wrap gap-3">
        <button type="button" onClick={() => choose("granted")} className="pps-btn pps-btn-primary min-h-10 px-5 py-2 text-sm">
          <span className="pps-btn__text">Accept</span>
        </button>
        <button type="button" onClick={() => choose("denied")} className="pps-btn pps-btn-light min-h-10 px-5 py-2 text-sm">
          <span className="pps-btn__text">Decline</span>
        </button>
      </div>
    </div>
  )
}
