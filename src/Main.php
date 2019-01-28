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
    add_action( 'transition_post_status', array($this, 'onTransition'), 10, 3 );
  }

  public function onTransition($new_status, $old_status, $post) {
    if ($new_status == 'publish' && $old_status != 'publish') {
      $this->onPostUpdated($post->ID, true);
    }
  }

  public function onPostUpdated($postID, $skip = false) {
    if ( 
        !(
          wp_is_post_revision( $postID ) 
          || wp_is_post_autosave( $postID ) 
          || get_post_status($postID) != 'publish'
          || (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
          || (get_post_type($postID) != 'job_listing')
        )
      ) {
        $last_update = get_option('tlc-job-alert-last-update', 0);
        if (($last_update >= time() -3) && !$skip) { return; }
        $this->events->emit('job-updated', [$postID]);
        update_option('tlc-job-alert-last-update', time());
    }
  }
}