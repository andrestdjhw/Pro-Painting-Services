import React from "react"
import ReactDOM from "react-dom/client"
import Navbar from "./components/Navbar"
import Footer from "./components/Footer"
import QuoteForm from "./components/QuoteForm"
import { initClickTracking } from "./tracking"

const data = window.ppsData

function mount(el, element) {
  if (el) ReactDOM.createRoot(el).render(element)
}

mount(document.getElementById("pps-navbar"), <Navbar data={data} />)
mount(document.getElementById("pps-footer"), <Footer data={data} />)

document.querySelectorAll(".pps-quote-form").forEach(el => {
  mount(el, <QuoteForm data={data} variant={el.dataset.variant} service={el.dataset.service} heading={el.dataset.heading} />)
})

initClickTracking()
