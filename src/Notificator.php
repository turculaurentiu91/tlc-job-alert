<?php namespace TlcJobAlert;

class Notificator {
  private $events;

  public function __construct($events) {
    $this->events = $events;

    $this->events->on('new-subscription', function($jobAlert) { 
      $this->notify_new_subscription($jobAlert);
    });
  }

  public function set_html_email_content_type() {
    return 'text/html';
  }

  private function send_email($args) {
    extract($args);
    $headers = isset($headers) ? $headers : '';
    $attachments = isset($attachments) ? $attachments : array();
    $templateData = isset($templateData) ? $templateData : array();
    apply_filters( 'wp_mail_from_name', 'Hoppenbrouwers Techniek B.V.' );
    add_filter( 'wp_mail_content_type', array($this, 'set_html_email_content_type') );
    $message = Helper::getTemplate($template, $templateData);
    wp_mail(
      $to,
      $subject,
      $message,
      $headers,
      $attachments
    );
    remove_filter( 'wp_mail_content_type', array($this, 'set_html_email_content_type') );
  }

  public function notify_new_subscription($jobAlert) {
    $this->send_email(array(
      'to' => $jobAlert['email'],
      'subject' => __("Hoppenbrouwers Techniek Job Alert", "tlc-job-alert"),
      'template' => 'newSubscriptionEmail',
    ));
  }
}