const { modules, path, paths } = require('./webpack.shared')
const merge = require('webpack-merge')
const {
  genericPlugins,
  manifestPlugin,
  devServerPlugin,
  cssExtractPlugin,
  assetLoaderPlugin,
  sourcemapPlugin,
  jsTranspilePlugin,
} = modules
const { source } = paths

const devConf = (env) => merge(devServerPlugin())
const prodConf = (env) => merge(cssExtractPlugin(env))

module.exports = ({ NODE_ENV: env }) => merge({
    mode: env,
    target: 'web',

    entry: {
      shittybrowsers: ['core-js/stable', 'regenerator-runtime'],
      client: ['react-hot-loader/patch', path.join(source, 'js', 'client')]
    },

    output: {
      filename: env === 'development' ? '[name].js' : '[name].[contenthash].js',
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
