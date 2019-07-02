import React from 'react'
import ReactDOM from 'react-dom'

import polyfiller from './lib/polyfiller'
import mutate from './lib/wds-mutations'
import createMenuToggles from './lib/create-menu-toggles'

import 'normalize.css'
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

  createMenuToggles('.k1-menu .menu-item-has-children > a')

  const { default: CommentList } = await import('./components/CommentList')
  const { default: CommentForm } = await import('./components/CommentList')

  Array.from(document.querySelectorAll('.k1-commentlist')).forEach(async (list) => {
    ReactDOM.render(<CommentList />, list)
  })

  Array.from(document.querySelectorAll('.k1-commentform')).forEach(async (form) => {
    ReactDOM.render(<CommentForm />, form)
  })
}
