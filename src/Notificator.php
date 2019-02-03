<?php namespace TlcJobAlert;

use \RedBeanPHP\R as R;

class Notificator {
  private $events;

  public function __construct($events) {
    $this->events = $events;

    $this->events->on('new-subscription', function($jobAlert) { 
      $this->notify_new_subscription($jobAlert);
    });

    $this->events->on('job-updated', function($jobID) {
      $this->verify_all_job_watches($jobID);
    });

    $this->events->on('cronjob', function() {
      $this->cronjob();
    });
  }

  public function cronjob() {
    
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

  private function verify_all_job_watches($jobID) {
    $job_watches = R::findAll('jobwatch');
    foreach($job_watches as $key => $jobwatch) {
      if (
          $this->job_match($jobID, $jobwatch)
          && $jobwatch->frequency == 'direct'
        ) {
        $this->notify_new_job($jobID, $jobwatch);
      }
    }
  }

  private function job_match($jobID, $jobAlerBean) {
    return (
      $this->verify_job_keyword_match($jobID, $jobAlerBean)
      && $this->verify_job_locations_match($jobID, $jobAlerBean)
      && $this->verify_job_discipline_match($jobID, $jobAlerBean)
      && $this->verify_job_contractType_match($jobID, $jobAlerBean)
    );
  }

  private function verify_job_locations_match($jobID, $jobAlerBean) {
    if ( 
      count($jobAlerBean->ownJoblocationList) == 0
    ) { return true; }
    if (taxonomy_exists('job_listing_region')) {
      $job_locations = wp_get_post_terms(
        $jobID,
        'job_listing_region',
        array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'names')
      );
      foreach($jobAlerBean->ownJoblocationList as $key => $location) {
        if (array_search($location->name, $job_locations) !== FALSE) {
          return true;
        }
      }
      return false;
      
    }
    return true;
  }

  private function verify_job_discipline_match($jobID, $jobAlerBean) {
    if (
      count($jobAlerBean->ownJobdisciplineList) == 0
    ) { return true; }
    if (taxonomy_exists('job_listing_category')) {
      $job_disciplines = wp_get_post_terms(
        $jobID,
        'job_listing_category',
        array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'names')
      );
      foreach($jobAlerBean->ownJobdisciplineList as $key => $discipline) {
        if (array_search($discipline->name, $job_disciplines) !== FALSE) {
          return true;
        }
      }
      return false;
      
    }
    return true;
  }

  private function verify_job_contractType_match($jobID, $jobAlerBean) {
    if (
      count($jobAlerBean->ownJobcontracttypeList) == 0
    ) { return true; }
    if (taxonomy_exists('job_listing_type')) {
      $job_contractTypes = wp_get_post_terms(
        $jobID,
        'job_listing_type',
        array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'names')
      );
      foreach($jobAlerBean->ownJobcontracttypeList as $key => $contractType) {
        if (array_search($contractType->name, $job_contractTypes) !== FALSE) {
          return true;
        }
      }
      return false;
      
    }
    return true;
  }

  private function verify_job_keyword_match($jobID, $jobAlerBean) {
    if (
      $jobAlerBean->keywords == ""
      || $jobAlerBean->keywords == null
      ) { return TRUE; }
    return strpos(
      get_post($jobID)->post_content,
      $jobAlerBean->keywords
    ) !== FALSE;
  }

  private function notify_new_job($jobID, $jobAlertBean) {
    $this->send_email(array(
      'to' => $jobAlertBean->email,
      'subject' => __("Hoppenbrouwers Techniek Job Alert", "tlc-job-alert"),
      'template' => 'newJobEmail',
      'templateData' => array(
        'jobID' => $jobID,
        'unsubscribe_link' => Helper::unsubscribe_link($jobAlertBean)
      ),
    ));
  }

  public function notify_new_subscription($jobAlert) {
    $this->send_email(array(
      'to' => $jobAlert['email'],
      'subject' => __("Hoppenbrouwers Techniek Job Alert", "tlc-job-alert"),
      'template' => 'newSubscriptionEmail',
    ));
  }
}