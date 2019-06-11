import React from 'react'
import PropTypes from 'prop-types'
import { hot } from 'react-hot-loader/root'

const Test = ({ x }) => (
  <p>This is a React component: {x}</p>
)

Test.propTypes = {
  x: PropTypes.string,
}

export default hot(function HotDemo () {
  return (
    <div>
      <Test x="cat" />
      <Test x="dog" />
      <Test x="dog" />
    </div>
  )
})
