# k1sul1/wordpress-theme-base

My idea of the perfect starting point for WordPress themes. Modern tooling in the frontend and utilities to keep you from repeating yourself.

I've shipped dozens of custom made WordPress sites and made a few of these starting points. None of them have been perfect, but this one works pretty damn well for me.

See also: [k1sul1/docker-wordpress-spa-template](https://github.com/k1sul1/docker-wordpress-cra-spa-template) for a local / production Docker environment with Let's Encrypt. It's built for single page applications but can be dumbed down by removing irrelevant services and configuration pieces.

Work in progress, forever.

## Features

Hopefully just enough for you to build the next best WordPress site, without getting in your way.
Everything from [k1sul1/k1kit](https://github.com/k1sul1/k1kit) and a few cherries on top. See it for more detailed description.

- Custom Gutenberg block toolkit
- Multilinguality support using Polylang, falling back to Core
- Reusable & combinable data-driven templates
  - Since there's no JSX support for PHP *yet*, they're admittedly a bit ugly
  - If you don't like the style, plugging Twig or some other solution should be possible
- Automagical asset manifests, ensures that visitors always see the latest assets and cachebusting is history
- REST API candy
- Transient overhaul
- Theme image optimization (Imagemin)
- Sourcemaps to help you locate troublemakers in CSS & JS
- Hot module reloading (HMR) for CSS & _compatible_ JS
- OOTB React support
- CSS preprocessor support
  - I prefer Stylus, if you want to use SCSS, that's easy. Install `node-sass` & `sass-loader`, replace `stylus-loader` with `scss-loader` and `.styl` with `.scss` in webpack config.
  - Switching isn't worth it though, Stylus is very similar to SCSS, key differences are that there are no partials and it resembles JS more
- PostCSS
  - Autoprefixer
  - Flexbugs fixer
- `<title>` is prefixed with the current environment to avoid confusion when working with multiple instances
- Namespaces

## Requirements

- PHP 7, I run 7.3 at the time of writing
- Node 10 on the host machine
- ACF PRO 5.8
  - If you don't want to create custom blocks, free version should work as well
- [k1 kit](https://github.com/k1sul1/k1kit)
  - The core of this theme

## Recommendations
- Polylang
  - If your site has only one language, don't install Polylang, and just change `languageSlugs` option in `functions.php` to match your language.
- The SEO Framework
  - Unlike Yoast, has no bloat, and has all the necessary features
  - Ensures that a `<title>` element always exists; WP frontpages often lack them.


## Install
Just dropping the folder works, but Composer is preferred.

```
composer require k1sul1/wordpress-theme-base
```

You might have to add dev-master as version until I create a release.

## Setup
Edit `config.json` to match your WordPress setup. Arguably not very hard. Run `npm install`.

To run webpack-dev-server (WDS), run `npm run dev`.
To create a production build, run `npm run build`.

In webpack-dev-server, CSS should update automatically without reloading, as should any module.hot compatible code. React should work out of the box. Due to recent ~~technological advancements~~ configuration changes,
it's now possible to run WDS "in the background". Previously you've had to open `http://yoursite.local:8080` and develop in that, but that shouldn't be necessary any more. If it doesn't work, try :8080 and submit an issue.

It is possible to use /wp-admin through WDS, but there be dragons. Possibly. You can't fight the dragons, but you can go around them by ensuring that you're always using the admin through the correct domain and not through the proxy. There's a snippet in src/js/admin.js that you can uncomment to ensure you're always using it through the correct domain.

## Help section
### I don't want to use React
That's ok. Removing React from the bundles is easy with minor configuration modifications.

In `.babelrc.js`, comment out the following
- @babel/plugin-transform-react-jsx
- react-hot-loader/babel
- @babel/preset-react

In addition to that, you have to edit `config/webpack.client.js`, and change `client` in `entries`. Comment or remove react-hot-loader:
`client: [/* 'react-hot-loader/patch', */path.join(source, 'js', 'client')]`

Now React won't be present.

### On webpack-dev-server
WDS makes your life a lot easier, especially now that it can run in the background.

Previously, it had a few drawbacks / limitations / annoyances. Because it's a proxy server, it runs in a different origin, and that might cause CORS problems. These problems could be dealt with programmatically, but it was not unusual to see form submissions failing, etc.

It's perfectly fine to use this theme without using WDS, simply run `npm run dev:noproxy` instead of `npm run dev`.

### Block creation

Create a file to blocks/. Name it whatever you want, just capitalize the first letter.

```php
<?php
// blocks/Example.php
namespace k1\Blocks;

class Example extends \k1\Block {
  public function render($data = []) { ?>
    <div class="example-block">
      <?=get_field('example')?>
    </div>
  <?php
  }

  /*
   * If you need to change the settings, that's easy.
   * If you don't, don't define this function.
   */
  public function getSettings() {
    $data = parent::getSettings();
    $data['mode'] = 'preview';

    return $data;
  }
}
```

Then just add a new field group, like you normally would. Just select your block as the location.

![sample](docs/block-fields.png)

### Can I call the custom blocks manually?
Yes. The template files already do so. This example showcases how you can also cache those manually called blocks.

```php
<?php
namespace k1;

$app = app();
$hero = $app->getBlock('Hero');

echo withTransient(capture([$hero, 'render'], [
  'content' => [
    'data' => '<h1>' . title($title) . '</h1>',
    'position' => 'centerBottom',
  ],
  'background' => [
    'backgroundMedia' => [
      'type' => 'image',
      'image' => [
        'data' => $thumb,
        'imagePosition' => 'centerCenter'
      ]
    ]
  ]
]), [
  'key' => 'indexHero',
  'options' => [
    'type' => 'manual-block',
    'expiry' => \HOUR_IN_SECONDS,
  ]
], $missReason);

echo "\n\n\n<!-- Block " . $hero->getName() . " cache: " . transientResult($missReason) . " -->";
```

When calling manually, you have to make sure that you use the same datastructure as ACF.

### I want to create Gutenberg blocks but I don't want to use ACF Blocks
Good luck. It's possible, but something that I'm not interested in supporting.

Create a new entry to `config/webpack.client.js` and start coding.

### How to create a new entry to Webpack configuration?
Pretty easy. Edit the `entry` object in the configuration file of your choice.
```
entry: {
  admin: ['react-hot-loader/patch', path.join(source, 'js', 'admin')],
  editor: ['react-hot-loader/patch', path.join(source, 'js', 'editor')], // this is new
},
```

Then create a file to src/js, in this case it's `editor.js`. If you want to generate a CSS file for the entry, import a CSS / Stylus file in it, just like in `client.js`.

After rebuilding or restarting the build, your new entry should be present in the generated asset manifest.

You don't have to include 'react-hot-loader/patch' in the entry, but it will not do any harm either.

### How does HMR work?
With black magic. Once you understand that, it's pretty simple. CSS is stateless and easy to replace, but JavaScript is trickier. You explicitly have to declare something as hot reloadable, but this can be done with abstractions, such as react-hot-loader.

Adding HMR support to your own JS is "easy", simply add
```
if (module.hot) {
  module.hot.accept('./path/to/file.js', (x) => {
    // do something
  })
}
```
to the relevant location.

### New .styl files aren't built without restarting the build or modifying the file which imports the offending file
I'm pretty sure this issue is in stylus-loader, there's not much I can do about it. It's only imports with globs (*) that cause this issue.

Best not to use glob, until this issue is fixed, which is probably never. [Unhelpful thread about the issue](https://github.com/shama/stylus-loader/issues/66).
