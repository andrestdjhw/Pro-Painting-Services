import React, { useMemo, useRef, useState } from "react"
import { track } from "../tracking"

const REQUIRED = {
  name: v => (v.trim() ? "" : "Please enter your name."),
  email: v => (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()) ? "" : "Please enter a valid email."),
  phone: v => (v.replace(/\D/g, "").length >= 10 ? "" : "Please enter a valid phone number."),
  location: v => (v.trim() ? "" : "Please enter the project location."),
  details: v => (v.trim() ? "" : "Please describe the painting work."),
  consent: v => (v ? "" : "Please agree to be contacted.")
}

function initialService(data, preset) {
  const fromUrl = new URLSearchParams(window.location.search).get("service")
  const valid = slug => data.services.some(s => s.slug === slug)
  if (preset && valid(preset)) return preset
  if (fromUrl && valid(fromUrl)) return fromUrl
  return ""
}

function Field({ id, label, required, error, className = "", children }) {
  return (
    <div className={className}>
      <label htmlFor={id} className="mb-1.5 block text-sm font-semibold">
        {label}
        {required && <span className="text-brand" aria-hidden="true"> *</span>}
      </label>
      {children}
      {error && (
        <p id={`${id}-error`} className="mt-1 text-sm text-brand" role="alert">
          {error}
        </p>
      )}
    </div>
  )
}

export default function QuoteForm({ data, variant = "full", service = "", heading }) {
  const uid = useMemo(() => Math.random().toString(36).slice(2, 8), [])
  const startedAt = useRef(Date.now())
  const started = useRef(false)
  const isMini = variant === "mini"

  const [values, setValues] = useState({
    name: "",
    company: "",
    email: "",
    phone: "",
    location: "",
    property_type: "",
    service: initialService(data, service),
    details: "",
    consent: false,
    website: "" // honeypot
  })
  const [errors, setErrors] = useState({})
  const [status, setStatus] = useState("idle") // idle | sending | success | error
  const [message, setMessage] = useState("")

  const id = name => `pps-${name}-${uid}`

  function onFocus() {
    if (!started.current) {
      started.current = true
      track("form_start", { form_variant: variant, service: values.service })
    }
  }

  function update(e) {
    const { name, type, checked, value } = e.target
    const next = type === "checkbox" ? checked : value
    setValues(v => ({ ...v, [name]: next }))
    if (errors[name] && REQUIRED[name]) {
      setErrors(err => ({ ...err, [name]: REQUIRED[name](next) }))
    }
  }

  function validateField(e) {
    const { name } = e.target
    if (REQUIRED[name]) setErrors(err => ({ ...err, [name]: REQUIRED[name](values[name]) }))
  }

  async function submit(e) {
    e.preventDefault()
    const found = {}
    Object.keys(REQUIRED).forEach(k => {
      const err = REQUIRED[k](values[k])
      if (err) found[k] = err
    })
    setErrors(found)
    if (Object.keys(found).length) {
      document.getElementById(id(Object.keys(found)[0]))?.focus()
      return
    }

    setStatus("sending")
    try {
      const res = await fetch(data.quoteEndpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ...values, started_at: startedAt.current, page: window.location.href })
      })
      const json = await res.json().catch(() => ({}))
      if (res.ok && json.ok) {
        setStatus("success")
        track("form_submit_success", { form_variant: variant, service: values.service })
        return
      }
      if (json.errors) setErrors(json.errors)
      setMessage(json.message || "Please review the highlighted fields.")
      setStatus("error")
    } catch {
      setMessage(`We couldn't send your request. Please call ${data.phone} or email ${data.email}.`)
      setStatus("error")
    }
  }

  if (status === "success") {
    return (
      <div className="rounded-lg bg-white p-8 text-center shadow-sm ring-1 ring-steel-200" role="status" aria-live="polite">
        <svg className="mx-auto h-12 w-12 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
          <circle cx="12" cy="12" r="10" />
          <path d="M8 12l3 3 5-6" />
        </svg>
        <p className="mt-4 text-xl font-semibold">Thank you. Your request has been sent to Pro Painting Services.</p>
      </div>
    )
  }

  const input = (name, props = {}) => (
    <input
      id={id(name)}
      name={name}
      value={values[name]}
      onChange={update}
      onBlur={validateField}
      onFocus={onFocus}
      aria-invalid={errors[name] ? "true" : undefined}
      aria-describedby={errors[name] ? `${id(name)}-error` : undefined}
      className="pps-input"
      {...props}
    />
  )

  return (
    <form onSubmit={submit} noValidate className="relative rounded-lg bg-white p-6 shadow-sm ring-1 ring-steel-200 sm:p-8">
      {heading && <h3 className="font-display text-3xl font-bold uppercase">{heading}</h3>}

      <div className="mt-6 grid gap-5 sm:grid-cols-2">
        <Field id={id("name")} label="Full Name" required error={errors.name}>
          {input("name", { autoComplete: "name" })}
        </Field>

        {!isMini && (
          <Field id={id("company")} label="Company / Property">
            {input("company", { autoComplete: "organization" })}
          </Field>
        )}

        <Field id={id("email")} label="Email" required error={errors.email}>
          {input("email", { type: "email", autoComplete: "email", inputMode: "email" })}
        </Field>

        <Field id={id("phone")} label="Phone" required error={errors.phone}>
          {input("phone", { type: "tel", autoComplete: "tel", inputMode: "tel" })}
        </Field>

        <Field id={id("location")} label="Project Location" required error={errors.location} className={isMini ? "" : "sm:col-span-2"}>
          {input("location", { placeholder: "City, State" })}
        </Field>

        <Field id={id("property_type")} label="Property Type">
          <select id={id("property_type")} name="property_type" value={values.property_type} onChange={update} onFocus={onFocus} className="pps-input">
            <option value="">Select…</option>
            {data.propertyTypes.map(t => (
              <option key={t.value} value={t.value}>{t.label}</option>
            ))}
          </select>
        </Field>

        {!isMini && (
          <Field id={id("service")} label="Service Needed">
            <select id={id("service")} name="service" value={values.service} onChange={update} onFocus={onFocus} className="pps-input">
              <option value="">Select…</option>
              {data.services.map(s => (
                <option key={s.slug} value={s.slug}>{s.name}</option>
              ))}
              <option value="other">Not sure / Other</option>
            </select>
          </Field>
        )}

        <Field id={id("details")} label="Project Details" required error={errors.details} className="sm:col-span-2">
          <textarea
            id={id("details")}
            name="details"
            rows={4}
            value={values.details}
            onChange={update}
            onBlur={validateField}
            onFocus={onFocus}
            placeholder="Buildings or units, surfaces, approximate size…"
            aria-invalid={errors.details ? "true" : undefined}
            aria-describedby={errors.details ? `${id("details")}-error` : undefined}
            className="pps-input"
          />
        </Field>

        {/* Honeypot: hidden from people, filled by bots */}
        <div className="absolute -left-[9999px]" aria-hidden="true">
          <label htmlFor={id("website")}>Website</label>
          <input id={id("website")} name="website" tabIndex={-1} autoComplete="off" value={values.website} onChange={update} />
        </div>

        <div className="sm:col-span-2">
          <label className="flex items-start gap-3 text-sm text-steel-600">
            <input
              id={id("consent")}
              type="checkbox"
              name="consent"
              checked={values.consent}
              onChange={update}
              onFocus={onFocus}
              aria-invalid={errors.consent ? "true" : undefined}
              aria-describedby={errors.consent ? `${id("consent")}-error` : undefined}
              className="mt-0.5 h-5 w-5 flex-none accent-brand"
            />
            <span>
              I agree to be contacted by Pro Painting Services about this request. See our{" "}
              <a href={data.privacyUrl} className="underline hover:text-brand">Privacy Policy</a>.
            </span>
          </label>
          {errors.consent && (
            <p id={`${id("consent")}-error`} className="mt-1 text-sm text-brand" role="alert">{errors.consent}</p>
          )}
        </div>
      </div>

      {status === "error" && message && (
        <p className="mt-5 rounded-md bg-brand/10 px-4 py-3 text-sm font-medium text-brand-dark" role="alert">
          {message}
        </p>
      )}

      <button
        type="submit"
        disabled={status === "sending"}
        className="pps-btn mt-6 w-full bg-brand text-white hover:bg-brand-dark disabled:opacity-60 sm:w-auto"
      >
        {status === "sending" ? "Sending…" : isMini ? "Send Project Request" : "Submit Request"}
      </button>
    </form>
  )
}
