/**
 * Global 3D tilt for every `.pps-card` (PHP templates and React components alike).
 *
 * One delegated pointer listener drives all cards, so cards rendered later
 * (e.g. the React mega menu) work without extra setup. The script only writes
 * CSS variables; the look lives in src/index.css (`.pps-card`).
 *
 * Skipped for touch input and for visitors who prefer reduced motion.
 */

const MAX_TILT_DEG = 8
const SHADOW_SHIFT_PX = 1.6 // shadow offset per degree of tilt

export function initTilt() {
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)")
  let active = null
  let frame = 0
  let pointer = null

  const reset = card => {
    card.classList.remove("is-tilting")
    ;["--rx", "--ry", "--gx", "--gy", "--sx", "--sy"].forEach(v => card.style.removeProperty(v))
  }

  const render = () => {
    frame = 0
    if (!active || !pointer) return
    const rect = active.getBoundingClientRect()
    const px = Math.min(Math.max((pointer.x - rect.left) / rect.width, 0), 1)
    const py = Math.min(Math.max((pointer.y - rect.top) / rect.height, 0), 1)
    const rx = (0.5 - py) * 2 * MAX_TILT_DEG // tilt up/down
    const ry = (px - 0.5) * 2 * MAX_TILT_DEG // tilt left/right

    active.style.setProperty("--rx", `${rx.toFixed(2)}deg`)
    active.style.setProperty("--ry", `${ry.toFixed(2)}deg`)
    active.style.setProperty("--gx", `${(px * 100).toFixed(1)}%`)
    active.style.setProperty("--gy", `${(py * 100).toFixed(1)}%`)
    // Shadow falls away from the raised edge
    active.style.setProperty("--sx", `${(-ry * SHADOW_SHIFT_PX).toFixed(1)}px`)
    active.style.setProperty("--sy", `${(18 + rx * SHADOW_SHIFT_PX).toFixed(1)}px`)
  }

  document.addEventListener(
    "pointermove",
    e => {
      if (e.pointerType === "touch" || reduceMotion.matches) return
      const card = e.target.closest?.(".pps-card")
      if (card !== active) {
        if (active) reset(active)
        active = card
        if (active) active.classList.add("is-tilting")
      }
      if (!active) return
      pointer = { x: e.clientX, y: e.clientY }
      if (!frame) frame = requestAnimationFrame(render)
    },
    { passive: true }
  )

  // Leaving the window or scrolling away while hovering: settle the card
  const release = () => {
    if (active) reset(active)
    active = null
  }
  document.addEventListener("pointerleave", release)
  window.addEventListener("blur", release)
}
