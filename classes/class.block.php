<?php
namespace k1;

/**
 * Boilerplate behind the magic
 */
abstract class Block {
  protected $name = null;

  /**
   * If you define your own constructor, be sure to call
   * parent::__construct in it.
   */
  public function __construct() {
    $this->register($this->getSettings());
  }

  public function register($data) {
    if (!function_exists('acf_register_block')) {
      throw new \Exception('ACF Pro is required to register blocks. If you don\'t have ACF Pro, remove all blocks from the /blocks folder.');
    }

    acf_register_block($data);
  }

  /**
   * In order to have less boilerplate, we extract the class name from the resulting class.
   */
  public function getName() {
    if (!$this->name) {
      $this->name = (new \ReflectionClass($this))->getShortName();
    }

    return $this->name;
  }

  /**
   * Chances are your block doesn't require changing anything, and you're good with the defaults.
   * If not, just redefine this function in your block.
   */
  public function getSettings() {
    return [
      'title' => $this->getName(),
      'name' => strtolower($this->getName()),
      'render_callback' => [$this, 'render'],
      'mode' => 'edit',
      'category' => 'layout',
      'supports' => [
        'align' => false,
        'mode' => true,
        'multiple' => true,
      ]
    ];
  }

  /**
   * Output the block
   */
  public function print(...$params) {
    // $name = $this->getName();
    // $function = "\\k1\\Blocks\\$name";
    echo $this->render(...$params);

    // echo $function(...$params);
  }

  /**
   * Print the template and capture the output.
   * Use cases include saving the result into a variable.
   */
  public function capture(...$params) {
    \ob_start();
    $this->print(...$params);

    return \ob_get_clean();
  }
}
