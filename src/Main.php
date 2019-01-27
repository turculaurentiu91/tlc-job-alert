<?php namespace TlcJobAlert;

use \RedBeanPHP\R as R;

class Main {
  private $adminPage;
  private $shortcodes;
  private $jobWatchController;
  private $notificator;
  private $events;

  public function __construct() {
    R::setup(
      "mysql:host=" . DB_HOST . ";" . "dbname=" . DB_NAME,
      DB_USER,
      DB_PASSWORD
    );
    
    $this->events = new \Sabre\Event\Emitter();
    $this->adminPage = new AdminPage();
    $this->shortcodes = new Shortcodes();
    $this->jobWatchController = new JobWatchController($this->events);
    $this->notificator = New Notificator($this->events);
    
    add_action('save_post', array($this, 'onPostUpdated'));
    add_action('untrashed_post', array($this, 'onPostUpdated'));
  }

  public function onPostUpdated($postID) {
    if ( wp_is_post_revision( $postID ) ) {
      return;
    }

    if (get_post_type($postID) == 'job_listing') {
      $this->events->emit('job-updated', [$postID]);
    }
  }
}