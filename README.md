# k1sul1/wordpress-theme-base

My idea of the perfect starting point for WordPress themes. Modern tooling in the frontend and utilities to keep you from repeating yourself.

I've shipped dozens of custom made WordPress sites and made a few of these starting points. Mistakes have been made, but everything in here has been battletested in production.

See also: [k1sul1/docker-wordpress-spa-template](https://github.com/k1sul1/docker-wordpress-cra-spa-template) for a local / production Docker environment with Let's Encrypt. It's built for single page applications but can be dumbed down by removing irrelevant services and configuration pieces.

WIP, some of the old source still exists, but tooling has been upgraded and everything should be functional.

## Install

```
composer require k1sul1/wordpress-theme-base
```

You might have to add dev-master as version until I create a release.

You should have ACF Pro (5.8+) installed and activated before activating the theme.

## Setup
Edit `config.json` to match your WordPress setup. Arguably not very hard. Run `npm install`.

To run webpack-dev-server, run `npm run dev`.

In webpack-dev-server, CSS should update automatically without reloading, as should any module.hot compatible code. React should work out of the box.

## Why would I want to use this?
The sheer size of the devDependencies list in package.json should be reason enough. Latest JavaScript tooling? Yes please. JavaScript might not be your forte, but how about Gutenberg?

This is how you create blocks.

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

And the best thing is that the codebase behind it small, so extending it should be straightforward.
