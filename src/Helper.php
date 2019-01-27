<?php namespace TlcJobAlert;

class Helper {
  public static function getTemplate($file, $data = array()) {
    extract($data);
    ob_start();
    require(TLC_JOB_ALERT_PATH_DIR . "src/templates/{$file}.php");
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }

  public static function randString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $randomString[0] = "_";
    return $randomString;
  }
}