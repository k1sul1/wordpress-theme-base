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
let config = {}

try {
  config = require(path.join(__dirname, '..', 'config.json'))
} catch (e) {
  console.log(e)
  console.log('')
  console.log('')
  console.log('The config file (config.json) appears to be missing, unable to run!')
  console.log('')
  console.log('')

  // exiting with error status kills the cat; pretend everything is fine
  process.exit(0)
}

const { webpack } = config
const host = process.env.HOST || webpack.serverAddress || 'localhost'
const port = process.env.PORT || 8080
const isHTTPS = webpack.wordpressURL.includes('https')
const publicPath = (isHTTPS ? 'https' : 'http') + `://${host}${webpack.publicPath}`

const manifestPlugin = (opts = {}) => ({
  plugins: [
    new Manifest({
      fileName: 'client-manifest.json',
      publicPath: '', // Don't add anything extra, just the filename.
      writeToFileEmit: 'true',
      seed: {},
      ...opts,
    }),
  ],
})

const devServerPlugin = (opts = {}) => {
  const isWin = /^win/.test(process.platform)
  const isMac = /^darwin/.test(process.platform)
  // const isTheOneAndOnly = !isWin && !isMac

  // Override publicPath
  const publicPath = (isHTTPS ? 'https' : 'http') + `://${host}:${port}${webpack.publicPath}`

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
        poll: isWin || isMac ? undefined : 1000, // Linux users get no love, blame webpack
        aggregateTimeout: 300,
      },

      open: process.env.OPEN !== 'false',
      hotOnly: true,
      clientLogLevel: 'none',
      publicPath,

      proxy: {
        '/': {
          target: webpack.wordpressURL,
          changeOrigin: true,
          autoRewrite: true,
          secure: false,
          headers: {
            'X-Proxy': 'webpack-dev-server',
          },
        },
      },

      // Allow access to WDS data from anywhere, including the standard non-proxied WP
      headers: {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
        'Access-Control-Allow-Headers': 'X-Requested-With, content-type, Authorization',
      },
    },

    plugins: [
      new WriteFile({
        test: /^(?!.*(hot)).*/,
      }),
      new HotModuleReplacement(),
    ],

    output: {
      publicPath, // Has to override any previous publicPaths, easiest way to ensure that to is to add this plugin last
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
          }),
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

      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            babelrc: true,
            cacheDirectory: true,
          },
        },
      },
    ],
  },
})

const cssExtractPlugin = (env) => ({
  module: {
    rules: [
      {
        test: /\.(css|styl)$/,
        use: [
          {
            loader: MiniCSSExtract.loader,
            options: {
              hmr: env === 'development',
            },
          },
          { loader: 'css-loader', options: { sourceMap } },
          { loader: 'postcss-loader', options: { sourceMap } },
          { loader: 'resolve-url-loader', options: { sourceMap } },
          { loader: 'stylus-loader', options: { sourceMap, preferPathResolver: 'webpack' } },
        ],
      },
    ],
  },

  plugins: [
    new MiniCSSExtract({
      filename: env !== 'development' ? '[name].[hash].css' : '[name].css',
    }),
  ],
})

const genericPlugins = (env) => {
  let conf = {
    output: {
      publicPath,
    },

    plugins: [
      new Copy([
        {
          from: 'src/img',
          to: 'img/',
        },
      ]),
      new Imagemin({
        disable: env !== 'production',

        optipng: {
          // https://github.com/imagemin/imagemin-optipng
          optimizationLevel: 3,
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
    ],
  }

  if (env !== 'development') {
    conf = merge(conf, {
      plugins: [
        new Terser({ parallel: true }),
        new OptimizeCSSAssets(),
      ],
    })
  }

  return conf
}

const sourcemapPlugin = (env) => ({
  devtool: env === 'development' ? 'cheap-module-source-map' : false,
})

const modules = {
  genericPlugins,
  manifestPlugin,
  devServerPlugin,
  cssExtractPlugin,
  assetLoaderPlugin,
  sourcemapPlugin,
}

const paths = {
  source: path.join(__dirname, '..', 'src'),
}

module.exports = {
  paths,
  path, // yes, the module
  modules,
}
