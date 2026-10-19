import React, { useEffect, useRef, useState } from "react"
import { ArrowIcon, PhoneIcon } from "./icons"

const linkClass = active =>
  `relative py-2 text-sm font-medium text-steel-200 transition-colors hover:text-white after:absolute after:inset-x-0 after:-bottom-1 after:h-0.5 after:bg-brand after:transition-transform ${
    active ? "after:scale-x-100" : "after:scale-x-0"
  }`

function isActive(url) {
  const path = new URL(url).pathname
  const current = window.location.pathname
  return path === "/" ? current === "/" : current.startsWith(path)
}

function Logo({ data }) {
  return (
    <a href={data.homeUrl} className="flex shrink-0 items-center" aria-label={`${data.siteName} home`}>
      {data.logoLightUrl || data.logoUrl ? (
        <img src={data.logoLightUrl || data.logoUrl} alt={data.siteName} width="132" height="56" className="h-12 w-auto lg:h-16" />
      ) : (
        <span className="flex flex-col leading-none">
          <span className="font-display text-3xl font-extrabold tracking-tight">
            P<span className="text-brand">P</span>S
          </span>
          <span className="text-[0.6rem] font-bold tracking-wide uppercase">Pro Painting Services</span>
        </span>
      )}
    </a>
  )
}

function ServiceImage({ service, className }) {
  return service.image ? (
    <img src={service.image} alt="" loading="lazy" className={`object-cover transition duration-500 group-hover:scale-105 ${className}`} />
  ) : (
    <div className={`bg-steel-200 ${className}`} aria-hidden="true" />
  )
}

function SpecialtyCard({ service, tabIndex }) {
  return (
    <a href={service.url} tabIndex={tabIndex} className="pps-card group flex h-full items-center gap-3 overflow-hidden rounded-sm border border-steel-200 bg-white pr-3">
      <div className="h-20 w-24 flex-none overflow-hidden">
        <ServiceImage service={service} className="h-full w-full" />
      </div>
      <div className="min-w-0 py-2">
        <p className="font-display text-sm leading-tight font-bold group-hover:text-brand">{service.name}</p>
        <p className="mt-1 line-clamp-2 text-xs leading-snug text-steel-500">{service.summary}</p>
      </div>
    </a>
  )
}

/** Full-width services mega menu (desktop). Opens on hover or with the chevron button. */
function ServicesMegaMenu({ item, data }) {
  const [open, setOpen] = useState(false)
  const ref = useRef(null)
  const closeTimer = useRef(null)
  const core = data.services.filter(s => !s.specialty)
  const specialty = data.services.filter(s => s.specialty)

  const show = () => {
    clearTimeout(closeTimer.current)
    setOpen(true)
  }
  // Small delay so moving the pointer from the link into the panel doesn't flicker it closed.
  const hide = () => {
    closeTimer.current = setTimeout(() => setOpen(false), 120)
  }

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

  useEffect(() => () => clearTimeout(closeTimer.current), [])

  return (
    <li ref={ref} className="flex h-full items-center" onMouseEnter={show} onMouseLeave={hide}>
      <a href={item.url} className={`${linkClass(isActive(item.url) || open)} ${open ? "text-white" : ""}`}>
        {item.label}
      </a>
      <button
        type="button"
        className={`ml-1 p-1 text-steel-200 hover:text-white ${open ? "text-white" : ""}`}
        aria-expanded={open}
        aria-controls="pps-services-menu"
        aria-label="Show services"
        onClick={() => setOpen(o => !o)}
      >
        <svg className={`h-4 w-4 transition-transform ${open ? "rotate-180" : ""}`} viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path d="M5.3 7.3a1 1 0 0 1 1.4 0L10 10.6l3.3-3.3a1 1 0 1 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 0-1.4z" />
        </svg>
      </button>

      <div
        id="pps-services-menu"
        hidden={!open}
        className="absolute inset-x-0 top-full border-t border-steel-200 border-b-2 border-b-brand bg-white text-ink shadow-2xl"
      >
        <div className="mx-auto max-w-site px-4 pt-8 sm:px-6 lg:px-8 xl:px-12">
          <p className="pps-eyebrow">Property Painting</p>
          <ul className="grid grid-cols-5 gap-4">
            {core.map(s => (
              <li key={s.slug}>
                <a href={s.url} className="pps-card group flex h-full flex-col overflow-hidden rounded-sm border border-steel-200 bg-white">
                  <div className="overflow-hidden">
                    <ServiceImage service={s} className="aspect-[16/10] w-full" />
                  </div>
                  <div className="flex-1 p-4">
                    <p className="font-display text-base leading-tight font-bold group-hover:text-brand">{s.name}</p>
                    <p className="mt-1.5 text-xs leading-relaxed text-steel-500">{s.summary}</p>
                  </div>
                </a>
              </li>
            ))}
          </ul>

          <p className="pps-eyebrow mt-7">Specialty Surface Painting</p>
          {/* Infinite left-to-right marquee. The second copy is decorative (hidden from AT / tab order). */}
          <div className="pps-marquee">
            <div className="pps-marquee__track">
              {[false, true].map(isCopy => (
                <ul key={isCopy ? "copy" : "main"} className="pps-marquee__set" aria-hidden={isCopy || undefined}>
                  {specialty.map(s => (
                    <li key={s.slug}>
                      <SpecialtyCard service={s} tabIndex={isCopy ? -1 : undefined} />
                    </li>
                  ))}
                </ul>
              ))}
            </div>
          </div>

          <div className="mt-7 flex items-center justify-between gap-6 border-t border-steel-200 py-5">
            <a href={item.url} className="group inline-flex items-center gap-2 text-xs font-bold tracking-[0.2em] uppercase hover:text-brand">
              Explore All Services <ArrowIcon className="pps-arrow text-brand" />
            </a>
            <ul className="flex items-center gap-4 text-[0.7rem] font-semibold tracking-[0.2em] text-steel-500 uppercase" aria-label="Areas served">
              {data.areas.map((area, i) => (
                <React.Fragment key={area}>
                  {i > 0 && <li aria-hidden="true" className="h-1.5 w-1.5 rotate-45 bg-brand" />}
                  <li>{area}</li>
                </React.Fragment>
              ))}
            </ul>
          </div>
        </div>
      </div>
    </li>
  )
}

/** Small dropdown for nav items with a `children` list (e.g. Contact → Service Areas). */
function NavDropdown({ item }) {
  const [open, setOpen] = useState(false)
  const ref = useRef(null)
  const closeTimer = useRef(null)
  const menuId = `pps-menu-${item.label.toLowerCase().replace(/\W+/g, "-")}`
  const active = item.children.some(c => isActive(c.url))

  const show = () => {
    clearTimeout(closeTimer.current)
    setOpen(true)
  }
  const hide = () => {
    closeTimer.current = setTimeout(() => setOpen(false), 120)
  }

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

  useEffect(() => () => clearTimeout(closeTimer.current), [])

  return (
    <li ref={ref} className="relative flex h-full items-center" onMouseEnter={show} onMouseLeave={hide}>
      <a href={item.url} className={`${linkClass(active || open)} ${open ? "text-white" : ""}`}>
        {item.label}
      </a>
      <button
        type="button"
        className={`ml-1 p-1 text-steel-200 hover:text-white ${open ? "text-white" : ""}`}
        aria-expanded={open}
        aria-controls={menuId}
        aria-label={`Show ${item.label} pages`}
        onClick={() => setOpen(o => !o)}
      >
        <svg className={`h-4 w-4 transition-transform ${open ? "rotate-180" : ""}`} viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path d="M5.3 7.3a1 1 0 0 1 1.4 0L10 10.6l3.3-3.3a1 1 0 1 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 0-1.4z" />
        </svg>
      </button>
      <ul id={menuId} hidden={!open} className="absolute top-full left-0 w-56 border-t-2 border-brand bg-white py-2 text-ink shadow-xl ring-1 ring-steel-200">
        {item.children.map(child => (
          <li key={child.url}>
            <a
              href={child.url}
              aria-current={isActive(child.url) && window.location.pathname === new URL(child.url).pathname ? "page" : undefined}
              className="block px-4 py-2.5 text-sm font-medium hover:bg-steel-50 hover:text-brand"
            >
              {child.label}
            </a>
          </li>
        ))}
      </ul>
    </li>
  )
}

function MobileMenu({ data, onClose }) {
  const [openSection, setOpenSection] = useState(null)
  const toggle = label => setOpenSection(o => (o === label ? null : label))

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
    <div id="pps-mobile-menu" className="fixed inset-x-0 top-[72px] bottom-0 z-40 overflow-y-auto bg-white text-ink lg:hidden">
      <ul className="divide-y divide-steel-200 px-4">
        {data.nav.map(item =>
          item.mega || item.children ? (
            <li key={item.label}>
              <button
                type="button"
                className="flex w-full items-center justify-between py-4 text-lg font-semibold"
                aria-expanded={openSection === item.label}
                onClick={() => toggle(item.label)}
              >
                {item.label}
                <span aria-hidden="true" className="text-2xl text-brand">{openSection === item.label ? "−" : "+"}</span>
              </button>
              {openSection === item.label && (
                <ul className="pb-4">
                  {item.mega ? (
                    <>
                      {data.services.map(s => (
                        <li key={s.slug}>
                          <a href={s.url} className="block py-2 pl-4 text-steel-700">{s.name}</a>
                        </li>
                      ))}
                      <li>
                        <a href={item.url} className="block py-2 pl-4 font-semibold text-brand">View all services</a>
                      </li>
                    </>
                  ) : (
                    item.children.map(c => (
                      <li key={c.url}>
                        <a href={c.url} className="block py-2 pl-4 text-steel-700">{c.label}</a>
                      </li>
                    ))
                  )}
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
        <a href={data.quoteUrl} className="pps-btn pps-btn-primary w-full" data-track="service_quote_click"><span className="pps-btn__text">Request a Quote</span> <ArrowIcon /></a>
        <a href={data.phoneHref} className="pps-btn pps-btn-outline w-full"><span className="pps-btn__text">Call {data.phone}</span></a>
      </div>
    </div>
  )
}

export default function Navbar({ data }) {
  const [menuOpen, setMenuOpen] = useState(false)
  const close = React.useCallback(() => setMenuOpen(false), [])

  return (
    <>
      <nav aria-label="Main" className="mx-auto flex h-full max-w-site items-center justify-between gap-6 px-4 sm:px-6 lg:px-8 xl:px-12">
        <Logo data={data} />

        <ul className="hidden h-full items-center gap-9 lg:flex">
          {data.nav.map(item =>
            item.mega ? (
              <ServicesMegaMenu key={item.label} item={item} data={data} />
            ) : item.children ? (
              <NavDropdown key={item.label} item={item} />
            ) : (
              <li key={item.label}>
                <a href={item.url} className={linkClass(isActive(item.url))} aria-current={isActive(item.url) ? "page" : undefined}>
                  {item.label}
                </a>
              </li>
            )
          )}
        </ul>

        <div className="hidden items-center gap-6 lg:flex">
          <a href={data.quoteUrl} className="pps-btn pps-btn-primary min-h-11 px-5 text-sm" data-track="service_quote_click">
            <span className="pps-btn__text">Request a Quote</span> <ArrowIcon />
          </a>
          <a href={data.phoneHref} className="flex items-center gap-2 text-sm font-semibold whitespace-nowrap text-white hover:text-steel-300">
            <PhoneIcon className="h-4 w-4" />
            {data.phone}
          </a>
        </div>

        <button
          type="button"
          className="-mr-2 p-2 text-white lg:hidden"
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
      <div className="fixed inset-x-0 bottom-0 z-40 grid grid-cols-2 border-t border-steel-800 bg-ink text-white lg:hidden">
        <a href={data.phoneHref} className="flex min-h-14 items-center justify-center gap-2 font-semibold">
          <PhoneIcon className="h-5 w-5 text-brand" />
          Call Now
        </a>
        <a href={data.quoteUrl} className="pps-chrome-fill flex min-h-14 items-center justify-center font-semibold text-ink" data-track="service_quote_click">
          Request a Quote
        </a>
      </div>
    </>
  )
}
