<?php namespace TlcJobAlert;

class Main {
  private $settings;

  public function __construct() {
    $this->settings = new Settings();
  }
}