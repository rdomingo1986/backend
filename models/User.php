<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {

  public function validateToken() {
    try {
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'validateToken'
      ));
      
      $tokenData = SessionJWT::isSignedIn($_POST['token']);

      return array(
        'token' => $tokenData['token'],
        'status' => $this->getUserStatusAccount($tokenData['payloadData']['id']),
        'success' => true,
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'TOKENUNREGISTERED' || $e->getMessage() == 'INVALIDTOKEN') {
        return array(
          'errorCode' => 'NOSESSION',
          'success' => false
        );
      }
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

	public function signin() {
    try {
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'signin'
      ));
  
      $this->load->helper('login');
      $tokenData = Login::userSignIn(array(
        'login' => trim($_POST['email']),
        'password' => $_POST['password']
      ));

      return array(
        'token' => $tokenData['token'],
        'status' => $this->getUserStatusAccount($tokenData['payloadData']['id']),
        'success' => true,
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'WRONGLOGINORPASSWORD') {
        return array(
          'errorCode' => 'WRONGLOGINORPASSWORD',
          'success' => false
        );
      }
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  public function signup() {
    try {
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'signup'
      ));
  
      Validation::isUniqueField(array(
        'databaseField' => 'email',
        'databaseTable' => 'user',
        'fieldValue' => trim($_POST['email'])
      ));
  
      $password = substr(Utils::randomMd5Hash(), 0, 8);
  
      $this->load->helper('login');
      Login::userSignUp(array(
        'login' => trim($_POST['email']),
        'password' => $password
      ));
      
      $this->load->helper('Mailer');
      Mailer::sendMail(array(
        'mails' => array($_POST['email']),
        'subject' => 'Se ha creado una cuenta',
        'body' => 'Su clave de acceso es: '.$password
      ));

      return array(
        'success' => true,
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'FIELDISNOTUNIQUE') {
        return array(
          'errorCode' => 'ACCOUNTALREADYEXISTS',
          'success' => false
        );
      }
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  public function requestRecoveryPassword() {
    try {
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'requestRecoveryPassword'
      ));
      
      $recoveryCode = Utils::randomMd5Hash(array(
        'databaseTable' => 'user',
        'databaseField' => 'passreset_code',
        'databaseFieldID' => 'id'
      ));

      $this->load->helper('login');
      Login::saveRecoveryPasswordCode(array(
        'login' => $_POST['email'],
        'recoveryCode' => $recoveryCode
      ));

      $this->load->helper('Mailer');
      Mailer::sendMail(array(
        'mails' => array($_POST['email']),
        'subject' => 'Ha recibido un codigo de recuperacion',
        'body' => 'Acceda al siguiente enlace para recuperar su contraseña: https://taxisfrontend.azurewebsites.net/resetpassword.html?recoveryCode='.$recoveryCode
      ));

      return array(
        'success' => true,
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'LOGINNOTEXISTS') {
        return array(
          'errorCode' => $e->getMessage(),
          'success' => false
        );
      } else if($e->getMessage() == 'ACCOUNTISNOTVALIDATED') {
        $password = $this->getUserPasswordByEmail($_POST['email']);
        $this->load->helper('Mailer');
        Mailer::sendMail(array(
          'mails' => array($_POST['email']),
          'subject' => 'Clave temporal',
          'body' => 'Su clave de acceso temporal es: '.$password
          
        ));
        return array(
          'errorCode' => $e->getMessage(),
          'success' => false
        );
      }
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  public function changeTemporalPassword() {
    try {
      $token = $_POST['token'];
      $tokenData = SessionJWT::isSignedIn(trim($token));
      
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'changeTemporalPassword'
      ));

      $this->load->helper('login');
      Login::userChangePassword(array(
        'userId' => $tokenData['payloadData']['id'],
        'oldPassword' => $_POST['oldPassword'],
        'password' => $_POST['password']
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token']
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'TOKENUNREGISTERED' || $e->getMessage() == 'INVALIDTOKEN' ) {
        return array(
          'errorCode' => 'NOSESSION',
          'success' => false
        );
      } else if($e->getMessage() == 'INVALIDCURRENTPASSWORD') {
        return array(
          'errorCode' => $e->getMessage(),
          'success' => false,
          'token' => $tokenData['token']
        );
      }
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  public function changePassword() {
    try {
      $token = $_POST['token'];
      $tokenData = SessionJWT::isSignedIn(trim($token));

      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'changePassword'
      ));
      
      $this->load->helper('login');
      Login::userChangePassword(array(
        'userId' => $tokenData['payloadData']['id'],
        'oldPassword' => $_POST['oldPassword'],
        'password' => $_POST['password']
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token']
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'TOKENUNREGISTERED' || $e->getMessage() == 'INVALIDTOKEN' ) {
        return array(
          'errorCode' => 'NOSESSION',
          'success' => false
        );
      } else if($e->getMessage() == 'INVALIDCURRENTPASSWORD') {
        return array(
          'errorCode' => $e->getMessage(),
          'success' => false,
          'token' => $tokenData['token']
        );
      }
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  public function recoveryPassword() {
		try {
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'recoveryPassword'
      ));
      
      $this->load->helper('login');
      Login::userChangePassword(array(
        'recoveryHash' => $_POST['recoveryHash'],
        'password' => $_POST['password']
      ));
      
      return array(
        'success' => true
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'TOKENUNREGISTERED' || $e->getMessage() == 'INVALIDTOKEN' ) {
        return array(
          'errorCode' => 'NOSESSION',
          'success' => false
        );
      } else if($e->getMessage() == 'INVALIDOREXPIREDHASH') {
        return array(
          'errorCode' => $e->getMessage(),
          'success' => false
        );
      }
      throw new Exception($e->getMessage(), $e->getCode());
    }
	}

  public function signout() {
    try {
      $tokenData = SessionJWT::isSignedIn(trim($_POST['token']));
      
      $this->load->helper('login');
      Login::userSignOut($_POST['token']);
      
      return array(
        'success' => true
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'TOKENUNREGISTERED' || $e->getMessage() == 'INVALIDTOKEN' ) {
        return array(
          'errorCode' => 'NOSESSION',
          'success' => false
        );
      }
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  public function getUserStatusAccount($id) {
    return $this->db->select('account_status')
      ->from('user')
      ->where('id', $id)
      ->get()
      ->row()
      ->account_status;
  }

  public function getUserPasswordByEmail($email) {
    return $this->encryption->decrypt(
      $this->db->select('password')
      ->from('user')
      ->where('email', $email)
      ->get()
      ->row()
      ->password
    );
  }
}
