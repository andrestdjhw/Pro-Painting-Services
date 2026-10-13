import React, { useEffect, useRef, useState } from "react"

function isActive(url) {
  const path = new URL(url).pathname
  const current = window.location.pathname
  return path === "/" ? current === "/" : current.startsWith(path)
}

function Logo({ data }) {
  return (
    <a href={data.homeUrl} className="flex shrink-0 items-center" aria-label={`${data.siteName} home`}>
      {data.logoUrl ? (
        <img src={data.logoUrl} alt={data.siteName} className="h-12 w-auto lg:h-14" />
      ) : (
        <span className="font-display text-xl leading-none font-bold uppercase lg:text-2xl">
          Pro <span className="text-brand">Painting</span> Services
        </span>
      )}
    </a>
  )
}

function ServicesDropdown({ item, services }) {
  const [open, setOpen] = useState(false)
  const ref = useRef(null)

  useEffect(() => {
    if (!open) return
    const onClick = e => ref.current && !ref.current.contains(e.target) && setOpen(false)
    const onKey = e => e.key === "Escape" && setOpen(false)
    document.addEventListener("mousedown", onClick)
    document.addEventListener("keydown", onKey)
    return () => {
      document.removeEventListener("mousedown", onClick)
      document.removeEventListener("keydown", onKey)
    }
  }, [open])

  return (
    <li ref={ref} className="relative" onMouseEnter={() => setOpen(true)} onMouseLeave={() => setOpen(false)}>
      <div className="flex items-center">
        <a href={item.url} className={`py-2 font-medium hover:text-brand ${isActive(item.url) ? "text-brand" : ""}`}>
          {item.label}
        </a>
        <button
          type="button"
          className="ml-1 p-1 hover:text-brand"
          aria-expanded={open}
          aria-controls="pps-services-menu"
          aria-label="Show services"
          onClick={() => setOpen(o => !o)}
        >
          <svg className={`h-4 w-4 transition-transform ${open ? "rotate-180" : ""}`} viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M5.3 7.3a1 1 0 0 1 1.4 0L10 10.6l3.3-3.3a1 1 0 1 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 0-1.4z" />
          </svg>
        </button>
      </div>
      <div id="pps-services-menu" hidden={!open} className="absolute top-full left-1/2 w-[34rem] -translate-x-1/2 pt-3">
        <ul className="grid grid-cols-2 gap-1 rounded-lg bg-white p-3 shadow-xl ring-1 ring-steel-200">
          {services.map(s => (
            <li key={s.slug}>
              <a href={s.url} className="block rounded-md px-3 py-2 text-sm font-medium hover:bg-steel-50 hover:text-brand">
                {s.name}
              </a>
            </li>
          ))}
          <li className="col-span-2 mt-1 border-t border-steel-200 pt-2">
            <a href={item.url} className="block rounded-md px-3 py-2 text-sm font-semibold text-brand hover:bg-steel-50">
              View all services &rarr;
            </a>
          </li>
        </ul>
      </div>
    </li>
  )
}

function MobileMenu({ data, onClose }) {
  const [servicesOpen, setServicesOpen] = useState(false)

  useEffect(() => {
    document.body.style.overflow = "hidden"
    const onKey = e => e.key === "Escape" && onClose()
    document.addEventListener("keydown", onKey)
    return () => {
      document.body.style.overflow = ""
      document.removeEventListener("keydown", onKey)
    }
  }, [onClose])

  return (
    <div id="pps-mobile-menu" className="fixed inset-x-0 top-[72px] bottom-0 z-40 overflow-y-auto bg-white lg:hidden">
      <ul className="divide-y divide-steel-200 px-4">
        {data.nav.map(item =>
          item.children ? (
            <li key={item.label}>
              <button
                type="button"
                className="flex w-full items-center justify-between py-4 text-lg font-semibold"
                aria-expanded={servicesOpen}
                onClick={() => setServicesOpen(o => !o)}
              >
                {item.label}
                <span aria-hidden="true" className="text-2xl text-brand">{servicesOpen ? "−" : "+"}</span>
              </button>
              {servicesOpen && (
                <ul className="pb-4">
                  {data.services.map(s => (
                    <li key={s.slug}>
                      <a href={s.url} className="block py-2 pl-4 text-steel-700">{s.name}</a>
                    </li>
                  ))}
                  <li>
                    <a href={item.url} className="block py-2 pl-4 font-semibold text-brand">View all services</a>
                  </li>
                </ul>
              )}
            </li>
          ) : (
            <li key={item.label}>
              <a href={item.url} className={`block py-4 text-lg font-semibold ${isActive(item.url) ? "text-brand" : ""}`}>
                {item.label}
              </a>
            </li>
          )
        )}
      </ul>
      <div className="space-y-3 p-4">
        <a href={data.quoteUrl} className="pps-btn w-full bg-brand text-white" data-track="service_quote_click">Request a Quote</a>
        <a href={data.phoneHref} className="pps-btn w-full border-2 border-ink">Call {data.phone}</a>
      </div>
    </div>
  )
}

export default function Navbar({ data }) {
  const [menuOpen, setMenuOpen] = useState(false)
  const close = React.useCallback(() => setMenuOpen(false), [])

  return (
    <>
      <nav aria-label="Main" className="mx-auto flex h-full max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8">
        <Logo data={data} />

        <ul className="hidden items-center gap-7 lg:flex">
          {data.nav.map(item =>
            item.children ? (
              <ServicesDropdown key={item.label} item={item} services={data.services} />
            ) : (
              <li key={item.label}>
                <a href={item.url} className={`py-2 font-medium hover:text-brand ${isActive(item.url) ? "text-brand" : ""}`}>
                  {item.label}
                </a>
              </li>
            )
          )}
        </ul>

        <div className="hidden items-center gap-5 lg:flex">
          <a href={data.phoneHref} className="font-semibold whitespace-nowrap hover:text-brand">{data.phone}</a>
          <a href={data.quoteUrl} className="pps-btn bg-brand text-white hover:bg-brand-dark" data-track="service_quote_click">
            Request a Quote
          </a>
        </div>

        <button
          type="button"
          className="-mr-2 p-2 lg:hidden"
          aria-expanded={menuOpen}
          aria-controls="pps-mobile-menu"
          aria-label={menuOpen ? "Close menu" : "Open menu"}
          onClick={() => setMenuOpen(o => !o)}
        >
          <svg className="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            {menuOpen ? <path d="M6 6l12 12M18 6L6 18" /> : <path d="M4 7h16M4 12h16M4 17h16" />}
          </svg>
        </button>
      </nav>

      {menuOpen && <MobileMenu data={data} onClose={close} />}

      {/* Persistent mobile call / quote bar */}
      <div className="fixed inset-x-0 bottom-0 z-40 grid grid-cols-2 border-t border-steel-200 bg-white lg:hidden">
        <a href={data.phoneHref} className="flex min-h-14 items-center justify-center gap-2 font-semibold">
          <svg className="h-5 w-5 text-brand" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1z" />
          </svg>
          Call Now
        </a>
        <a href={data.quoteUrl} className="flex min-h-14 items-center justify-center bg-brand font-semibold text-white" data-track="service_quote_click">
          Request a Quote
        </a>
      </div>
    </>
  )
}
