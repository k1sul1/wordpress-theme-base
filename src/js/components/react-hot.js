import React from 'react'
import { hot } from 'react-hot-loader/root'

const Test = ({ x }) => (
  <p>This is a React component: {x}</p>
)

export default hot(function HotDemo() {
  return (
    <div>
      <Test x="cat" />
      <Test x="dog" />
    </div>
  )
})
