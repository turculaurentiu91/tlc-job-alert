<?php namespace TlcJobAlert;

class Helper {
  public static function getTemplate($file) {
    ob_start();
    require(TLC_JOB_ALERT_PATH_DIR . "src/templates/{$file}.php");
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }
}