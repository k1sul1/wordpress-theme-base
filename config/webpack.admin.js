const { modules, path, paths } = require('./webpack.shared')
const merge = require('webpack-merge')
const {
  genericPlugins,
  manifestPlugin,
  cssExtractPlugin,
  assetLoaderPlugin,
  sourcemapPlugin,
  jsTranspilePlugin,
} = modules
const { source } = paths

const devConf = (env) => merge(cssExtractPlugin(env))
const prodConf = (env) => merge(cssExtractPlugin(env))

module.exports = ({ NODE_ENV: env }) => merge({
    mode: env,
    target: 'web',

    entry: {
      admin: ['react-hot-loader/patch', path.join(source, 'js', 'admin')]
      // editor: ['react-hot-loader/patch', path.join(source, 'js', 'editor')]
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
  manifestPlugin({ fileName: 'admin-manifest.json' }),
  sourcemapPlugin(env) ,
  env === 'development' ? devConf(env) : prodConf(env)
)
