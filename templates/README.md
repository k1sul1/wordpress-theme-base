# Templates
Templates are functions that are used to create reusable components in your theme. By (ab)using the nature of PHP, you can focus on the real thing, instead of writing boilerplate. To create and use them, all you need to do is add a file to the /templates folder. They're read automatically.

## Sample

```php
<?php
namespace k1\Templates;

function Button($params = []) {
  $params = params([
    'text' => 'Hello!',
  ], $params); ?>

  <button>
    <?=$params['text']?>
  </button><?php
}
```

You don't have to indent like that, linters certainly hate it, but that's the best possible compromise I've been able to come up with.

## Possibilities
Endless. Because they are just normal functions, you can pass in other functions, such as other templates, or even WP_Query objects.
