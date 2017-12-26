<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Drivers extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Driver', 'driver');
  }

	public function index() { }

	public function listAll() {
		CORS::checkCORS();
		echo json_encode($this->driver->listAll());
  }
  
  public function insertOne() {
		CORS::checkCORS();
		echo json_encode($this->driver->insertOne());
  }

  public function getById() {
    CORS::checkCORS();
		echo json_encode($this->driver->getById());
  }

  public function editOne() { 
    CORS::checkCORS();
		echo json_encode($this->driver->editOne());
  }
  
  public function deleteOne() {
		CORS::checkCORS();
		echo json_encode($this->driver->deleteOne());
	}
}
