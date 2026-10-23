import React from "react"
import { PhoneIcon, SocialLink } from "./icons"

const linkClass = "transition-colors hover:text-white"

export default function Footer({ data }) {
  const year = new Date().getFullYear()
  const links = [...data.socials]
  if (data.gbpUrl && !links.some(s => s.label === "Google")) links.push({ label: "Google", url: data.gbpUrl })

  return (
    <footer className="relative isolate overflow-hidden text-sm text-steel-300">
      {data.markUrl && <img src={data.markUrl} alt="" aria-hidden="true" loading="lazy" className="pps-watermark -right-10 -bottom-24 w-[22rem]" />}
      <div className="mx-auto flex max-w-site flex-col gap-8 px-4 py-10 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8 xl:px-12">
        <a href={data.homeUrl} className="inline-block shrink-0 text-white" aria-label={`${data.siteName} home`}>
          {data.logoLightUrl ? (
            <img src={data.logoLightUrl} alt={data.siteName} width="132" height="56" className="h-14 w-auto" loading="lazy" />
          ) : (
            <span className="flex flex-col leading-none">
              <span className="font-display text-3xl font-extrabold tracking-tight">
                P<span className="text-brand">P</span>S
              </span>
              <span className="text-[0.6rem] font-bold tracking-wide uppercase">Pro Painting Services</span>
            </span>
          )}
        </a>

        <nav aria-label="Footer">
          <ul className="flex flex-wrap gap-x-8 gap-y-2">
            {data.nav.map(item => (
              <li key={item.label}><a href={item.url} className={linkClass}>{item.label}</a></li>
            ))}
          </ul>
        </nav>

        <div className="flex flex-wrap items-center gap-6">
          {links.length > 0 && (
            <div className="flex gap-4">
              {links.map(s => <SocialLink key={s.label} {...s} />)}
            </div>
          )}
          <a href={data.phoneHref} className={`flex items-center gap-2 font-semibold text-white ${linkClass}`}>
            <PhoneIcon className="h-4 w-4" />
            {data.phone}
          </a>
        </div>
      </div>

      <div className="border-t border-steel-800">
        <div className="mx-auto flex max-w-site flex-col gap-2 px-4 py-5 text-xs sm:px-6 lg:flex-row lg:justify-between lg:px-8 xl:px-12">
          <address className="not-italic">
            {data.address.join(", ")} ·{" "}
            <a href={`mailto:${data.email}`} className={linkClass}>{data.email}</a>
          </address>
          <p>{data.serviceArea}</p>
        </div>
      </div>

      {/* Copyright / legal / agency credit */}
      <div className="border-t border-steel-800 bg-black/30">
        <div className="mx-auto flex max-w-site flex-col gap-3 px-4 py-4 text-xs sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8 xl:px-12">
          <div className="flex flex-wrap items-center gap-x-4 gap-y-1">
            <p>&copy; {year} {data.siteName}. All rights reserved.</p>
            <a href={data.privacyUrl} className={linkClass}>Privacy Policy</a>
            <a href={data.termsUrl} className={linkClass}>Terms and Conditions</a>
            <button type="button" data-cookie-settings className={linkClass}>Cookie Settings</button>
          </div>
          <p>
            Site by{" "}
            <a href={data.agencyUrl} target="_blank" rel="noopener" className="font-semibold text-white transition-colors hover:text-brand">
              {data.agencyName}
            </a>
          </p>
        </div>
      </div>
    </footer>
  )
}
