<?php namespace TlcJobAlert;

class AdminPage {

  public function __construct() {
    add_action('admin_menu', array($this, 'add_submenu'));
  }

  public function add_submenu() {
    \add_submenu_page(
      'edit.php?post_type=job_listing',
      __("TLC Job Alert", "tlc-job-alert"),
      __("TLC Job Alert", "tlc-job-alert"),
      'manage_options',
      'tlc-job-ealert-admin',
      array($this, 'render_admin_page')
    );
  }

  public function render_admin_page()
  {
    echo(Helper::getTemplate('admin-menu'));
  }
}