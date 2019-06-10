<?php
namespace k1;

/**
 * Boilerplate behind the magic
 */
abstract class Block {
  protected $name = null;

  /**
   * In order to have less boilerplate, we extract the class name the resulting class.
   * That name is used to call the block template function.
   */
  public function getName() {
    if (!$this->name) {
      $this->name = (new \ReflectionClass($this))->getShortName();
    }

    return $this->name;
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
