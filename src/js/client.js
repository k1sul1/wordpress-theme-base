import React from 'react'
import ReactDOM from 'react-dom'

import polyfiller from './lib/polyfiller'
import mutate from './lib/wds-mutations'

import '../styl/client.styl'

/**
 * Don't want to use React? Just remove any React related imports
 * and do the config changes described in the README.
 */

polyfiller({
  condition: window.Promise,
  src: window.wptheme.corejs,
}, () => polyfiller({ // If you're not using async/await, remove this call
  condition: window.regeneratorRuntime,
  src: window.wptheme.regeneratorRuntime,
}, main))

async function main () {
  /**
   * Fix *most* links and forms when running in WDS (:8080)
   *
   * Seemingly no effect when running in the correct origin.
   */
  if (module.hot) {
    mutate({ replaceUrlWith: window.wptheme.wpurl })
  }

  const { default: App } = await import('./components/react-hot')
  ReactDOM.render(<App />, document.querySelector('.site-footer'))
}
