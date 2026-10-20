import React from "react"
import { MailIcon, PhoneIcon, PinIcon, SocialLink } from "./icons"

export default function Topbar({ data }) {
  const socials = [...data.socials]
  if (data.gbpUrl && !socials.some(s => s.label === "Google")) socials.push({ label: "Google", url: data.gbpUrl })

  return (
    // Three columns (1fr | auto | 1fr) so the address sits exactly in the middle
    <div className="mx-auto flex h-full max-w-site items-center justify-between gap-6 px-4 text-xs text-steel-300 sm:px-6 xl:grid xl:grid-cols-[1fr_auto_1fr] lg:px-8 xl:px-12">
      <ul className="flex items-center gap-6">
        <li>
          <a href={data.phoneHref} className="flex items-center gap-2 font-medium whitespace-nowrap transition-colors hover:text-white">
            <PhoneIcon className="h-3.5 w-3.5 text-brand" />
            {data.phone}
          </a>
        </li>
        <li>
          <a href={`mailto:${data.email}`} className="flex items-center gap-2 font-medium whitespace-nowrap transition-colors hover:text-white">
            <MailIcon className="h-3.5 w-3.5 text-brand" />
            {data.email}
          </a>
        </li>
      </ul>

      {/* Geotag: opens the Google Business Profile on Google Maps (1280px+; no room on smaller screens) */}
      <a
        href={data.gbpUrl || `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(data.address.join(", "))}`}
        target="_blank"
        rel="noopener noreferrer"
        className="hidden items-center gap-2 font-medium whitespace-nowrap transition-colors hover:text-white xl:flex"
        aria-label={`Our office: ${data.address.join(", ")} (opens Google Maps)`}
        data-track="click_directions"
      >
        <PinIcon className="h-3.5 w-3.5 text-brand" />
        {data.address.join(", ")}
      </a>

      {socials.length > 0 ? (
        <ul className="flex items-center gap-4 xl:justify-self-end" aria-label="Social media">
          {socials.map(s => (
            <li key={s.label}>
              <SocialLink {...s} />
            </li>
          ))}
        </ul>
      ) : (
        <p>{data.serviceArea.split(".")[0]}.</p>
      )}
    </div>
  )
}
