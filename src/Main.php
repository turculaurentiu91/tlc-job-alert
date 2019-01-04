<?php namespace TlcJobAlert;

class Main {
  private $adminPage;

  public function __construct() {
    $this->adminPage = new AdminPage();
  }
}