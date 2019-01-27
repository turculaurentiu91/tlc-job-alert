<?php namespace TlcJobAlert;

use \RedBeanPHP\R as R;
use Rakit\Validation\Validator;

class JobWatchController {

  public function __construct() {
    add_action('rest_api_init', array($this, 'register_endpoints'));
  }

  private function get_validator($data) {
    $validator = new Validator;
    return $validator->make($data, [
      'name' => 'required|alpha_spaces',
      'email' => 'required|email',
      'keywords' => 'required|alpha_spaces',
      'frequency' => 'required|in:direct,weekly,two-weeks',
      'locations' => 'array',
      'disciplines' => 'array',
      'contractTypes' => 'array',
    ]);
  }

  private function prepare_item($item) {
    $returnItem = array(
      'id' => $item->id,
      'name' => $item->name,
      'email' => $item->email,
      'keywords' => $item->keywords,
      'frequency' => $item->frequency,
      'locations' => array(),
      'disciplines' => array(),
      'contractTypes' => array(),
    );

    foreach($item->ownJoblocationList as $key => $value) {
      $returnItem['locations'][] = $value->name;
    }

    foreach($item->ownJobdisciplineList as $key => $value) {
      $returnItem['disciplines'][] = $value->name;
    }

    foreach($item->ownJobcontracttypeList as $key => $value) {
      $returnItem['contractTypes'][] = $value->name;
    }

    return $returnItem;
  }

  private function prepare_array($itemList) {
    return array_values(array_map(array($this, 'prepare_item'), $itemList));
  }

  public function register_endpoints() {
    $namespace = 'tlc';
    $base = 'job-alert';

    register_rest_route(
      $namespace,
      "/" . $base,
      array(
        array(
          'methods' => \WP_REST_Server::READABLE,
          'callback' => array($this, 'get_items'),
          //'permission_callback' => array($this, 'permission_check'),
        ),
        array(
          'methods' => \WP_REST_Server::CREATABLE,
          'callback' => array($this, 'create_item'),
          //'permission_callback' => array($this, 'permission_check'),
        ),
      )
    );

    register_rest_route(
      $namespace,
      "/" . $base . "/(?P<id>[\d]+)",
      array(
        array(
          'methods' => \WP_REST_Server::READABLE,
          'callback' => array($this, 'get_item'),
          //'premission_callback' => array($this, 'permission_check'),
        ),
        array(
          'methods' => \WP_REST_Server::EDITABLE,
          'callback' => array($this, 'edit_item'),
          //'permission_callback' => array($this, 'permission_check'),
        ),
        array(
          'methods' => \WP_REST_Server::DELETABLE,
          'callback' => array($this, 'delete_item'),
          //'permission_callback' => array($this, 'permission_check'),
        ),
      )
    );
  }

  public function premission_check() {
    return true;
  }

  public function get_items($request) {
    // For some reason Redbeans returns an associative array, so conversion to indexed is needed
   // $data = array_values(R::findAll('jobwatch'));
   $data = R::findAll('jobwatch');
    return new \WP_REST_Response($this->prepare_array($data), 200);
  }

  public function get_item($request) {
    $job_watch = R::load('jobwatch', $request['id']);
    if ($job_watch->ID == 0) {
      return new \WP_Error('no_job_watch', 'No job watch found', array('status' => 404));
    }
    return new \WP_REST_Response($this->prepare_item($job_watch), 200);
  }

  public function delete_item($request) {
    $job_watch = R::load('jobwatch', $request['id']);
    if ($job_watch->ID == 0) {
      return new \WP_Error('no_job_watch', 'No job watch found', array('status' => 404));
    }
    R::trash($job_watch);
    $tlc_event->emit('delete-subscription', [$job_watch]);
    return new \WP_REST_Response("", 200);
  }

  private function validate($data) {
    $validation = $this->get_validator($data);
    $validation->validate();
    if($validation->fails()) {
      $errors = $validation->errors();
      return new \WP_Error('invalid_data', $errors->firstOfAll(), array('status' => '403'));
    }

    return false;
  }

  public function create_item($request) {
    global $tlc_event;

    $req = $request->get_json_params();

    $validation = $this->validate($req);
    if ($validation !== false) { return $validation; }

    $job_watch = R::dispense('jobwatch');
    $job_watch->name = $req['name'];
    $job_watch->email = $req['email'];
    $job_watch->keywords = $req['keywords'];
    $job_watch->frequency = $req['frequency'];

    if (isset($req['locations'])) {
      foreach ($req['locations'] as $key => $value) {
       $location = R::dispense('joblocation');
       $location->name = $value;
       $job_watch->ownJoblocationList[] = $location; 
      }
    }

    if (isset($req['disciplines'])) {
      foreach ($req['disciplines'] as $key => $value) {
       $discipline = R::dispense('jobdiscipline');
       $discipline->name = $value;
       $job_watch->ownJobdisciplineList[] = $discipline; 
      }
    }

    if (isset($req['contractTypes'])) {
      foreach ($req['contractTypes'] as $key => $value) {
       $contracttype = R::dispense('jobcontracttype');
       $contracttype->name = $value;
       $job_watch->ownJobcontracttypeList[] = $contracttype; 
      }
    }

    R::store($job_watch);
    $tlc_event->emit('new-subscription', [$job_watch]);
    return new \WP_REST_Response($this->prepare_item($job_watch), 200);
  }

  public function edit_item($request) {
    $req = $request->get_json_params();

    $validation = $this->validate($req);
    if ($validation !== false) { return $validation; }

    $job_watch = R::load('jobwatch', $request['id']);
    if ($job_watch->ID == 0) {
      return new \WP_Error('no_job_watch', 'No job watch found', array('status' => 404));
    }

    $job_watch->name = $req['name'];
    $job_watch->email = $req['email'];
    $job_watch->keywords = $req['keywords'];
    $job_watch->frequency = $req['frequency'];
    $job_watch->ownJoblocationList = [];
    $job_watch->ownJobdisciplineList = [];
    $job_watch->ownJobcontracttypeList = []; 

    if (isset($req['locations'])) {
      foreach ($req['locations'] as $key => $value) {
       $location = R::dispense('joblocation');
       $location->name = $value;
       $job_watch->ownJoblocationList[] = $location; 
      }
    }

    if (isset($req['disciplines'])) {
      foreach ($req['disciplines'] as $key => $value) {
       $discipline = R::dispense('jobdiscipline');
       $discipline->name = $value;
       $job_watch->ownJobdisciplineList[] = $discipline; 
      }
    }

    if (isset($req['contractTypes'])) {
      foreach ($req['contractTypes'] as $key => $value) {
       $contracttype = R::dispense('jobcontracttype');
       $contracttype->name = $value;
       $job_watch->ownJobcontracttypeList[] = $contracttype; 
      }
    }

    R::store($job_watch);
    $tlc_event->emit('edit-subscription', [$job_watch]);
    return new \WP_REST_Response($this->prepare_item($job_watch), 200);
  }
}