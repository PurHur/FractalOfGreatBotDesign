<?php

class Command {

  protected $key = null;

  public function __construct($key) {
    $this->key = $key

    $this->initialize()
  }

  protected function initialize() {}

  protected function run() {
    if ($this->handle()) {
      return true;
    }
    return false;
  }

  protected function handle() {
    return false;
  }

}
