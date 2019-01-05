<?php namespace TlcJobAlert;

class Shortcodes {

  public function __construct() {
    add_action('init', array($this, 'registerShortcodes'));
  }

  public function registerShortcodes() {
    add_shortcode(
      'tlc_job_alert_form',
      function() {
        return Helper::getTemplate('form-shortcode');
      }
    );
  }
}