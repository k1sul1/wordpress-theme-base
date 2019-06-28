/**
 * Change links and stuff. Used when running in webpack-dev-server
 */
export default function mutate ({ replaceUrlWith = 'http://localhost' }) {
  return Array.from(document.querySelectorAll('a, form')).map((element) => {
    const url = window.location.origin

    switch (element.tagName) {
    case 'A': {
      if (element.href) {
        element.href = element.href.replace(replaceUrlWith, url)
      }
      break
    }

    case 'FORM': {
      const action = element.getAttribute('action')

      if (action) {
        element.setAttribute('action', action.replace(replaceUrlWith, url))
      }

      break
    }

    default:
    }

    return element
  })
}
