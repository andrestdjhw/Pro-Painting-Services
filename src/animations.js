/**
 * Scroll-reveal, hero entrance, parallax and header scroll state.
 *
 * Progressive enhancement: content is only hidden for animation once this
 * script adds `.pps-motion` to <html>, and never for visitors who prefer
 * reduced motion. Animations replay every time a section scrolls back into
 * view (elements reset while fully off-screen).
 *
 * Opt-in hooks (templates):
 *   data-reveal="up|left|right|zoom|fade|wipe"  explicit reveal
 *   data-hero      hero section: entrance + slow zoom on its background image
 *   data-parallax  section whose direct <img> child drifts on scroll
 * Everything else inside `main section .pps-container` is revealed automatically.
 */

const STAGGER_MS = 90
const MAX_STAGGER = 6

function revealDirection(block, container) {
  // Two-column layouts slide in from opposite sides
  const isTwoCol = container.classList.contains("grid") && container.children.length === 2
  if (!isTwoCol) return "up"
  return block === container.children[0] ? "left" : "right"
}

function tagAutoReveal() {
  const containers = document.querySelectorAll("main section:not([data-hero]) .pps-container")
  containers.forEach(container => {
    ;[...container.children].forEach(block => {
      if (block.matches(".sr-only, [data-reveal]") || block.closest("[data-reveal]") !== null) return

      const grid = block.matches(".grid") ? block : block.querySelector(":scope > .grid, :scope > div > .grid")
      const items = grid ? [...grid.children].filter(el => !el.matches("[aria-hidden='true']")) : []

      if (grid && items.length >= 2 && items.length <= 12) {
        // Stagger the cards of a grid instead of moving the whole grid
        items.forEach((item, i) => {
          item.dataset.reveal = "up"
          item.style.setProperty("--reveal-delay", `${Math.min(i, MAX_STAGGER) * STAGGER_MS}ms`)
        })
        if (block !== grid) {
          ;[...block.children].filter(el => el !== grid && !grid.contains(el)).forEach(el => (el.dataset.reveal = "up"))
        }
      } else {
        block.dataset.reveal = revealDirection(block, container)
      }
    })
  })
}

// How long the reveal transition stays active (longest transition + max stagger)
const ANIMATING_MS = 1700

function observeReveals() {
  const els = document.querySelectorAll("[data-reveal]")
  if (!els.length) return

  const timers = new WeakMap()

  const io = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        const el = entry.target
        clearTimeout(timers.get(el))

        if (entry.isIntersecting && entry.intersectionRatio >= 0.12) {
          // Enter: animate in. `is-animating` only scopes the transition, so once it's
          // removed the element's own hover transitions apply again.
          el.classList.add("is-animating", "is-visible")
          timers.set(el, setTimeout(() => el.classList.remove("is-animating"), ANIMATING_MS))
        } else if (!entry.isIntersecting) {
          // Fully out of view: reset instantly so it replays next time it's reached
          el.classList.remove("is-animating", "is-visible")
        }
      })
    },
    { threshold: [0, 0.12] }
  )
  els.forEach(el => io.observe(el))
}

/** Replays the hero entrance whenever the hero scrolls back into view. */
function observeHeroes() {
  const heroes = document.querySelectorAll("[data-hero]")
  const io = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add("is-playing")
      else entry.target.classList.remove("is-playing")
    })
  })
  heroes.forEach(hero => {
    hero.classList.add("is-playing")
    io.observe(hero)
  })
}

function initParallax() {
  const layers = [...document.querySelectorAll("[data-parallax] > img, [data-hero] > img")]
  if (!layers.length) return

  let ticking = false
  const update = () => {
    const vh = window.innerHeight
    layers.forEach(img => {
      const rect = img.parentElement.getBoundingClientRect()
      if (rect.bottom < 0 || rect.top > vh) return
      const progress = (rect.top + rect.height / 2 - vh / 2) / vh // -1 … 1 around viewport center
      img.style.setProperty("--parallax-y", `${(progress * -60).toFixed(1)}px`)
    })
    ticking = false
  }
  const onScroll = () => {
    if (!ticking) {
      ticking = true
      requestAnimationFrame(update)
    }
  }
  update()
  window.addEventListener("scroll", onScroll, { passive: true })
  window.addEventListener("resize", onScroll)
}

function initHeaderState() {
  const nav = document.getElementById("pps-navbar")
  if (!nav) return
  const update = () => nav.classList.toggle("is-scrolled", window.scrollY > 8)
  update()
  window.addEventListener("scroll", update, { passive: true })
}

export function initAnimations() {
  initHeaderState()

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches
  if (reduceMotion || !("IntersectionObserver" in window)) return

  document.documentElement.classList.add("pps-motion")
  tagAutoReveal()
  observeReveals()
  observeHeroes()
  initParallax()
}
