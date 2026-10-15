/**
 * Project photo carousels (inc/projects.php → pps_project_card). Native scroll-snap does the
 * sliding (so swipe works on touch); this adds prev/next buttons and the "n / total" counter.
 */
export function initGalleries() {
  document.querySelectorAll("[data-gallery]").forEach(gallery => {
    const track = gallery.querySelector(".pps-gallery__track")
    const current = gallery.querySelector("[data-gallery-current]")
    if (!track) return

    const count = track.children.length
    // Guard: width can be 0 while hidden/laid out, which would yield NaN
    const index = () => (track.clientWidth ? Math.round(track.scrollLeft / track.clientWidth) : 0)
    const go = step => {
      const target = Math.min(Math.max(index() + step, 0), count - 1)
      track.scrollTo({ left: target * track.clientWidth })
    }

    gallery.querySelector("[data-gallery-prev]")?.addEventListener("click", () => go(-1))
    gallery.querySelector("[data-gallery-next]")?.addEventListener("click", () => go(1))
    track.addEventListener("keydown", e => {
      if (e.key === "ArrowLeft") { e.preventDefault(); go(-1) }
      if (e.key === "ArrowRight") { e.preventDefault(); go(1) }
    })
    if (current) {
      track.addEventListener("scroll", () => (current.textContent = Math.min(index() + 1, count)), { passive: true })
    }
  })
}
