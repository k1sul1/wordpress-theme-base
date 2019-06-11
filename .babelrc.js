module.exports = {
  "plugins": [
    // Stage 2; EcmaScript draft
    ["@babel/plugin-proposal-decorators", { "legacy": true }],
    "@babel/plugin-proposal-function-sent",
    "@babel/plugin-proposal-numeric-separator",
    "@babel/plugin-proposal-throw-expressions",
    "@babel/plugin-proposal-export-namespace-from",

    // Stage 3; EcmaScript candidate
    "@babel/plugin-syntax-import-meta",
    "@babel/plugin-syntax-dynamic-import",
    ["@babel/plugin-proposal-class-properties", { "loose": false }],
    "@babel/plugin-proposal-json-strings",

    // Stable
    "@babel/plugin-transform-react-jsx",
    "babel-plugin-transform-object-rest-spread",
    "react-hot-loader/babel"
  ],
  "presets": [
    ["@babel/preset-env", { "modules": false }],
    "@babel/preset-react"
  ]
}
