<?php namespace TlcJobAlert;

class Main {
  private $adminPage;
  private $shortcodes;

  public function __construct() {
    $this->adminPage = new AdminPage();
    $this->shortcodes = new Shortcodes();
  }
}