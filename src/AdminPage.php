<?php namespace TlcJobAlert;

class AdminPage {

  public function __construct() {
    add_action('admin_menu', array($this, 'add_submenu'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
  }

  public function add_submenu() {
    \add_submenu_page(
      'edit.php?post_type=job_listing',
      __("Job Alert", "tlc-job-alert"),
      __("Job Alert", "tlc-job-alert"),
      'manage_options',
      'tlc-job-alert-admin',
      array($this, 'render_admin_page')
    );
  }

  public function enqueue_styles() {

  }

  public function enqueue_assets() {
    if (isset($_GET['page'])) {
      if ($_GET['page'] == 'tlc-job-alert-admin') {
        wp_enqueue_script(
          'vuejs',
          'https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js',
          array(),
          '2.5.17'
        );
    
        wp_enqueue_script(
          'vuex',
          'https://cdnjs.cloudflare.com/ajax/libs/vuex/3.0.1/vuex.js',
          array(),
          '3.0.1'
        );

        wp_enqueue_script(
          'axios',
          'https://unpkg.com/axios/dist/axios.min.js',
          array(),
          'latest'
        );

        wp_enqueue_script(
          'job-alert-admin',
          TLC_JOB_ALERT_PATH_URL . 'assets/js/admin-menu.js',
          array(),
          '1.0.0',
          true
        );

        wp_enqueue_style(
          'job-alert-admin-css',
          TLC_JOB_ALERT_PATH_URL . 'assets/css/admin-menu.css',
          array(),
          '1.0.0'
        );
      }
    }
  }

  public function render_admin_page()
  {
    echo(Helper::getTemplate('admin-menu'));
  }
}