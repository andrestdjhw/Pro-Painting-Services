import React from "react"

function Column({ title, children }) {
  return (
    <div>
      <h2 className="font-display text-lg font-bold tracking-wider text-white uppercase">{title}</h2>
      <div className="mt-4">{children}</div>
    </div>
  )
}

const linkClass = "hover:text-white transition-colors"

export default function Footer({ data }) {
  const year = new Date().getFullYear()

  return (
    <footer className="text-steel-300">
      <div className="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 lg:px-8">
        <div>
          <a href={data.homeUrl} className="inline-block" aria-label={`${data.siteName} home`}>
            {data.logoUrl ? (
              <img src={data.logoUrl} alt={data.siteName} className="h-14 w-auto" loading="lazy" />
            ) : (
              <span className="font-display text-2xl leading-none font-bold text-white uppercase">
                Pro <span className="text-brand">Painting</span> Services
              </span>
            )}
          </a>
          <p className="mt-4 max-w-xs text-sm">
            Commercial, multifamily and hospitality painting across New Mexico.
          </p>
          <a href={data.quoteUrl} className="pps-btn mt-6 bg-brand text-white hover:bg-brand-dark" data-track="service_quote_click">
            Request a Quote
          </a>
        </div>

        <Column title="Contact">
          <address className="space-y-3 not-italic">
            <p>
              <a href={data.phoneHref} className={`text-lg font-semibold text-white ${linkClass}`}>{data.phone}</a>
            </p>
            <p>
              <a href={`mailto:${data.email}`} className={`break-all ${linkClass}`}>{data.email}</a>
            </p>
            <p>
              {data.address[0]}<br />{data.address[1]}
            </p>
            <p className="text-sm">{data.serviceArea}</p>
          </address>
        </Column>

        <Column title="Services">
          <ul className="space-y-2 text-sm">
            {data.services.map(s => (
              <li key={s.slug}><a href={s.url} className={linkClass}>{s.name}</a></li>
            ))}
          </ul>
        </Column>

        <Column title="Company">
          <ul className="space-y-2 text-sm">
            {data.nav.filter(item => item.label !== "Services").map(item => (
              <li key={item.label}><a href={item.url} className={linkClass}>{item.label}</a></li>
            ))}
            {data.gbpUrl && (
              <li><a href={data.gbpUrl} className={linkClass} target="_blank" rel="noopener">Google Business Profile</a></li>
            )}
            {data.socials.map(s => (
              <li key={s.label}><a href={s.url} className={linkClass} target="_blank" rel="noopener">{s.label}</a></li>
            ))}
          </ul>
        </Column>
      </div>

      <div className="border-t border-steel-800">
        <div className="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-xs sm:flex-row sm:justify-between sm:px-6 lg:px-8">
          <p>&copy; {year} {data.siteName}. All rights reserved.</p>
          <a href={data.privacyUrl} className={linkClass}>Privacy Policy</a>
        </div>
      </div>
    </footer>
  )
}
