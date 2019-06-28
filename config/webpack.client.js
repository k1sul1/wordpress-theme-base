const { modules, path, paths } = require('./webpack.shared')
const merge = require('webpack-merge')
const {
  genericPlugins,
  manifestPlugin,
  devServerPlugin,
  cssExtractPlugin,
  assetLoaderPlugin,
  sourcemapPlugin,
} = modules
const { source } = paths

const isDevServer = process.argv.find((v) => v.includes('webpack-dev-server'))

const devConf = (env) => (isDevServer
  ? merge(devServerPlugin(), cssExtractPlugin(env))
  : merge(cssExtractPlugin(env))
)
const prodConf = (env) => merge(cssExtractPlugin(env))

module.exports = ({ NODE_ENV: env }) => merge(
  {
    mode: env,
    target: 'web',

    entry: {
      corejs: ['core-js/stable'],
      regeneratorRuntime: ['regenerator-runtime'],
      client: ['react-hot-loader/patch', path.join(source, 'js', 'client')],
    },

    output: {
      filename: env === 'development' ? '[name].js' : '[name].[contenthash].js',
    },

    // Replace react-dom with @hot-loader/react-dom
    resolve: {
      alias: env === 'development'
        ? {
          'react-dom': '@hot-loader/react-dom',
        }
        : {},
    },

    node: {
      fs: 'empty',
    },
  },
  genericPlugins(env),
  assetLoaderPlugin(env),
  manifestPlugin({ fileName: 'client-manifest.json' }),
  sourcemapPlugin(env),
  env === 'development' ? devConf(env) : prodConf(env)
)
