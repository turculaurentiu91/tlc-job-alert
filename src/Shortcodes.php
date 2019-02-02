<?php namespace TlcJobAlert;

class Shortcodes {

  public function __construct() {
    add_action('init', array($this, 'registerShortcodes'));
    add_action('init', array($this, 'register_scripts'));
  }

  public function register_scripts() {

    wp_register_script(
      'axios',
      'https://unpkg.com/axios/dist/axios.min.js',
      array(),
      'latest'
    );

    wp_register_script(
      'select2',
      'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js',
      array(),
      'latest'
    );

    wp_enqueue_style(
      'select2',
      'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css',
      array(),
      '1.0.0'
    );

    wp_register_script(
      'tlc-jobs-alert-form-shortcode',
      TLC_JOB_ALERT_PATH_URL . '/assets/js/form-shortcode.js',
      array('select2'),
      '1.0.0'
    );
  }

  public function enqueue_scripts() {
    wp_enqueue_script('tlc-jobs-alert-form-shortcode');
    wp_enqueue_script('axios');
    wp_enqueue_script('select2');
    wp_enqueue_style('select2');
  }

  public function registerShortcodes() {
    add_shortcode(
      'tlc_job_alert_form',
      function() {
        $this->enqueue_scripts();
        return Helper::getTemplate('form-shortcode');
      }
    );
  }
}