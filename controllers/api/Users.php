<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('User', 'user');
  }

	public function index() { }

	public function validateToken() {
		CORS::checkCORS();
		echo json_encode($this->user->validateToken());
	}

	public function signin() {
		CORS::checkCORS();
		echo json_encode($this->user->signin());
	}

	public function signup() {
		CORS::checkCORS();
		echo json_encode($this->user->signup());
	}

	public function requestRecoveryPassword() {
		CORS::checkCORS();
		echo json_encode($this->user->requestRecoveryPassword());
	}

	public function changeTemporalPassword() {
		CORS::checkCORS();
		echo json_encode($this->user->changeTemporalPassword());
	}

	public function changePassword() {
		CORS::checkCORS();
		echo json_encode($this->user->changePassword());
	}

	public function recoveryPassword() {
		CORS::checkCORS();
		echo json_encode($this->user->recoveryPassword());
	}

	public function signout() {
		CORS::checkCORS();
		echo json_encode($this->user->signout());
	}
}
