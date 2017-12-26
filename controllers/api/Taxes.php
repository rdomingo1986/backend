<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Taxes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Tax', 'tax');
  }

	public function index() { }

	public function listAll() {
		CORS::checkCORS();
		echo json_encode($this->tax->listAll());
  }
  
  public function insertOne() {
		CORS::checkCORS();
		echo json_encode($this->tax->insertOne());
  }

  public function getById() {
    CORS::checkCORS();
		echo json_encode($this->tax->getById());
  }

  public function editOne() { 
    CORS::checkCORS();
		echo json_encode($this->tax->editOne());
  }
  
  public function deleteOne() {
		CORS::checkCORS();
		echo json_encode($this->tax->deleteOne());
	}
}
