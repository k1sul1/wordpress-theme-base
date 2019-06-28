function loadScript (src, done) {
  const js = document.createElement('script')

  js.src = src
  js.onload = () => {
    done()
  }
  js.onerror = () => {
    done(new Error(`Failed to load script ${src}`))
  }

  document.head.appendChild(js)
}

/**
 * Ensure that dependency (condition) is met and run callback.
 * Promises would make it a lot nicer, but Promises might not be supported.
 *
 * Heavily inspired by https://philipwalton.com/articles/loading-polyfills-only-when-needed/
 */
function polyfiller ({ condition, src }, fn) {
  if (!src || !fn) {
    return false
  }

  if (!condition) {
    loadScript(src, fn)
  } else {
    fn()
  }
}

export default polyfiller
