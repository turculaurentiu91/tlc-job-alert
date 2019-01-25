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
      'location' => 'array',
      'discipline' => 'array',
      'contract-type' => 'array',
    ]);
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
   $data = R::exportAll(R::findAll('jobwatch'));
    return new \WP_REST_Response($data, 200);
  }

  public function get_item($request) {
    $job_watch = R::load('jobwatch', $request['id']);
    if ($job_watch->ID == 0) {
      return new \WP_Error('no_job_watch', 'No job watch found', array('status' => 404));
    }
    return new \WP_REST_Response(R::exportAll($job_watch)[0], 200);
  }

  public function delete_item($request) {
    $job_watch = R::load('jobwatch', $request['id']);
    if ($job_watch->ID == 0) {
      return new \WP_Error('no_job_watch', 'No job watch found', array('status' => 404));
    }
    R::trash($job_watch);
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

    $req = $request->get_json_params();

    $validation = $this->validate($req);
    if ($validation !== false) { return $validation; }

    $job_watch = R::dispense('jobwatch');
    $job_watch->name = $req['name'];
    $job_watch->email = $req['email'];
    $job_watch->keywords = $req['keywords'];
    $job_watch->frequency = $req['frequency'];

    if (isset($req['location'])) {
      foreach ($req['location'] as $key => $value) {
       $location = R::dispense('joblocation');
       $location->name = $value;
       $job_watch->ownJoblocationList[] = $location; 
      }
    }

    if (isset($req['discipline'])) {
      foreach ($req['discipline'] as $key => $value) {
       $discipline = R::dispense('jobdiscipline');
       $discipline->name = $value;
       $job_watch->ownJobdisciplineList[] = $discipline; 
      }
    }

    if (isset($req['contract-type'])) {
      foreach ($req['contract-type'] as $key => $value) {
       $contracttype = R::dispense('jobcontracttype');
       $contracttype->name = $value;
       $job_watch->ownJobcontracttypeList[] = $contracttype; 
      }
    }

    R::store($job_watch);

    return new \WP_REST_Response(R::exportAll($job_watch)[0], 200);
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

    if (isset($req['location'])) {
      foreach ($req['location'] as $key => $value) {
       $location = R::dispense('joblocation');
       $location->name = $value;
       $job_watch->ownJoblocationList[] = $location; 
      }
    }

    if (isset($req['discipline'])) {
      foreach ($req['discipline'] as $key => $value) {
       $discipline = R::dispense('jobdiscipline');
       $discipline->name = $value;
       $job_watch->ownJobdisciplineList[] = $discipline; 
      }
    }

    if (isset($req['contract-type'])) {
      foreach ($req['contract-type'] as $key => $value) {
       $contracttype = R::dispense('jobcontracttype');
       $contracttype->name = $value;
       $job_watch->ownJobcontracttypeList[] = $contracttype; 
      }
    }

    R::store($job_watch);

    return new \WP_REST_Response(R::exportAll($job_watch)[0], 200);
  }
}