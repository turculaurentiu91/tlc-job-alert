<?php namespace TlcJobAlert;

use \RedBeanPHP\R as R;

class Main {
  private $adminPage;
  private $shortcodes;

  public function __construct() {
    R::setup(
      "mysql:host=" . DB_HOST . ";" . "dbname=" . DB_NAME,
      DB_USER,
      DB_PASSWORD
    );
    
    $this->adminPage = new AdminPage();
    $this->shortcodes = new Shortcodes();
  }
}