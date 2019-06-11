import 'regenerator-runtime' // If you don't need async/await, comment this line to reduce bundle size
import React from 'react'
import ReactDOM from 'react-dom'

import '../styl/client.styl'

/**
 * Don't want to use React? Just remove any React related imports, no need to remove
 * React from package.json. React won't be present in production bundle
 * if you haven't used it.
 */
document.addEventListener('DOMContentLoaded', async () => {
  const { default: App } = await import('./components/react-hot')

  ReactDOM.render(<App />, document.querySelector('.site-footer'))
})
