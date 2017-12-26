<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicles extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Vehicle', 'vehicle');
  }

	public function index() { }

	public function listAll() {
		CORS::checkCORS();
		echo json_encode($this->vehicle->listAll());
  }
  
  public function insertOne() {
		CORS::checkCORS();
		echo json_encode($this->vehicle->insertOne());
  }

  public function getById() {
    CORS::checkCORS();
		echo json_encode($this->vehicle->getById());
  }

  public function editOne() { 
    CORS::checkCORS();
		echo json_encode($this->vehicle->editOne());
  }
  
  public function deleteOne() {
		CORS::checkCORS();
		echo json_encode($this->vehicle->deleteOne());
	}
}
