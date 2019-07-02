export default function createMenuToggles(selector) {
  return Array.from(document.querySelectorAll(selector)).map((link) => {
    const toggle = document.createElement('span')
    toggle.classList.add('k1-menu__toggle')

    toggle.addEventListener('click', (e) => {
      link.parentNode.classList.toggle('open')

      e.preventDefault()
    })

    link.classList.add('k1-initialized')
    link.appendChild(toggle)

    return toggle
  })
}
