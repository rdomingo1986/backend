<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleTypes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('VehicleType', 'vehicletype');
  }

	public function index() { }

	public function listAll() {
		CORS::checkCORS();
		echo json_encode($this->vehicletype->listAll());
  }
  
  public function insertOne() {
		CORS::checkCORS();
		echo json_encode($this->vehicletype->insertOne());
  }

  public function getById() {
    CORS::checkCORS();
		echo json_encode($this->vehicletype->getById());
  }

  public function editOne() { 
    CORS::checkCORS();
		echo json_encode($this->vehicletype->editOne());
  }
  
  public function deleteOne() {
		CORS::checkCORS();
		echo json_encode($this->vehicletype->deleteOne());
	}
}
