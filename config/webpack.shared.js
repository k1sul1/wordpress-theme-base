const path = require('path')
const merge = require('webpack-merge')
const Copy = require('copy-webpack-plugin')
const Imagemin = require('imagemin-webpack-plugin').default
const Manifest = require('webpack-manifest-plugin')
const WriteFile = require('write-file-webpack-plugin')
const OptimizeCSSAssets = require('optimize-css-assets-webpack-plugin')
const Terser = require('terser-webpack-plugin')
const HotModuleReplacement = require('webpack').HotModuleReplacementPlugin
const MiniCSSExtract = require('mini-css-extract-plugin')

const sourceMap = true

const manifestPlugin = (opts = {}) => ({
  plugins: [
    new Manifest({
      fileName: 'client-manifest.json',
      writeToFileEmit: 'true',
      ...opts,
    })
  ]
})

const devServerPlugin = (opts = {}) => {
  const isWin = /^win/.test(process.platform)
  const isMac = /^darwin/.test(process.platform)
  // const isTheOneAndOnly = !isWin && !isMac

  let config = {}
  try {
    config = require(path.join(__dirname, '..', 'config.json'))
  } catch (e) {
    console.log(e)
    console.log('')
    console.log('')
    console.log(`The config file (config.json) appears to be missing, unable to run WDS!`)
    console.log('')
    console.log('')

    // exiting with error status kills the cat; pretend everything is fine
    process.exit(0)
  }
  const host = process.env.HOST || config.wdsUrl || 'localhost'
  const port = process.env.PORT || 8080;
  const isHTTPS = config.proxy.includes('https')
  const publicPath = isHTTPS ? 'https' : 'http' + `://${host}:${port}${config.publicPath}`

  return merge({
    devServer: {
      host,
      port,
      https: isHTTPS,
      stats: 'errors-only',

      overlay: {
        errors: true,
        warnings: false,
      },

      watchOptions: {
        poll: isWindows || isMac ? undefined : 1000, // Linux users get no love, blame webpack
        aggregateTimeout: 300,
      },

      open: process.env.OPEN === 'false' ? false : true,
      hotOnly: true,
      clientLogLevel: 'none',
      publicPath,

      proxy: {
        '/': {
          target: config.proxy,
          changeOrigin: true,
          autoRewrite: true,
          secure: false,
          headers: {
            'X-Proxy': 'webpack-dev-server',
          }
        }
      }
    },

    plugins: [
      new WriteFile({
        test: /^(?!.*(hot)).*/,
      }),
      new HotModuleReplacement(),
    ],

    output: {
      publicPath,
    },

    module: {
      rules: [
        {
          test: /\.(styl|css)$/,
          use: [
            {
              loader: 'style-loader',
              options: {
                sourceMap,
                singleton: true,
              }
            },
            { loader: 'css-loader', options: { sourceMap } },
            { loader: 'postcss-loader', options: { sourceMap } },
            { loader: 'resolve-url-loader', options: { sourceMap } },
            { loader: 'stylus-loader', options: { sourceMap } }
          ],
        },
      ],
    },
  })
}

const assetLoaderPlugin = (env) => ({
  module: {
    rules: [
      {
        test: /\.(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/i,
        include: /node_modules/,

        use: {
          loader: 'file-loader',
          options: Object.assign({}, {
            name: env === 'production' ? '[name].[chunkhash].[ext]' : '[name].[ext]',
          }, options),
        },
      },

      {
        test: /\.(jpe?g|png|gif|svg|ttf|otf|eot|woff(2)?)$/i,
        include: paths.source,

        use: [
          {
            loader: 'file-loader',
          },
        ],
      },
    ],
  },
})

// env will be useful in the future with next release of the plugin
const cssExtractPlugin = (env) => ({
  module: {
    rules: [
      {
        test: /\.(css|styl)$/,
        use: [
          { loader: MiniCSSExtract.loader },
          { loader: 'css-loader', options: { sourceMap } },
          { loader: 'postcss-loader', options: { sourceMap } },
          { loader: 'resolve-url-loader', options: { sourceMap } },
          { loader: 'stylus-loader', options: { sourceMap } }
        ],
      },
    ],
  },

  plugins: [
    new MiniCSSExtract({
      filename: env === 'production' ? '[name].[hash].css' : '[name].css',
    }),
  ],
})

const genericPlugins = (env) => ({
  plugins: [
    new Copy([
      {
        from: 'img',
      }
    ]),
    new Imagemin({
      disable: env !== 'production',

      optipng: {
        // https://github.com/imagemin/imagemin-optipng
        optimizationLevel: 3
      },

      gifsicle: {
        // https://github.com/imagemin/imagemin-gifsicle
        optimizationLevel: 2,
      },

      jpegtran: {
        // https://github.com/imagemin/imagemin-jpegtran
        arithmetic: true,
        progressive: true,
      },

      svgo: {
        plugins: [
          { removeScriptElement: true },
          { removeViewBox: false },
          { removeDimensions: true },
        ],
      },
    }),

    env !== 'development' && new Terser({ parallel: true }),
    env !== 'development' && new OptimizeCSSAssets(),
  ]
})

const sourcemapPlugin = (env) => ({
  devtool: env === 'development' ? 'cheap-module-source-map' : false
})

const modules = {
  genericPlugins,
  manifestPlugin,
  devServerPlugin,
  cssExtractPlugin,
  assetLoaderPlugin,
  sourcemapPlugin,
  jsTranspilePlugin,
}

const paths = {
  source: path.join(__dirname, '..', 'src'),
}

module.exports = {
  paths,
  path, // yes, the module
  modules,
}
