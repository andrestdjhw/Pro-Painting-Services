import React from "react"
import { MailIcon, PhoneIcon, SocialLink } from "./icons"

export default function Topbar({ data }) {
  const socials = [...data.socials]
  if (data.gbpUrl && !socials.some(s => s.label === "Google")) socials.push({ label: "Google", url: data.gbpUrl })

  return (
    <div className="mx-auto flex h-full max-w-site items-center justify-between gap-6 px-4 text-xs text-steel-300 sm:px-6 lg:px-8 xl:px-12">
      <ul className="flex items-center gap-6">
        <li>
          <a href={data.phoneHref} className="flex items-center gap-2 font-medium transition-colors hover:text-white">
            <PhoneIcon className="h-3.5 w-3.5 text-brand" />
            {data.phone}
          </a>
        </li>
        <li>
          <a href={`mailto:${data.email}`} className="flex items-center gap-2 font-medium transition-colors hover:text-white">
            <MailIcon className="h-3.5 w-3.5 text-brand" />
            {data.email}
          </a>
        </li>
      </ul>

      {socials.length > 0 ? (
        <ul className="flex items-center gap-4" aria-label="Social media">
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
