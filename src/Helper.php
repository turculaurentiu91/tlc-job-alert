<?php namespace TlcJobAlert;

use \RedBeanPHP\R as R;

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

  public static function encode_unsubscribe_token($alertBean) {
    return base64_encode(json_encode(array(
      'email' => $alertBean->email,
      'id' => $alertBean->ID
    )));
  }
  
  public static function decode_unsubscribe_token($token) {
    $data = json_decode(base64_decode($token), true);
    return R::load('jobwatch', $data['id']);
  }

  public static function unsubscribe_link($bean) {
    return 
      get_permalink(get_option('tlc-job-alert-form-page-id')) 
      . '?unsubscribe_token=' 
      . self::encode_unsubscribe_token($bean);
  }
}