<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Model {

  public function listAll() {
    try {
      $token = $_POST['token'];
      $tokenData = SessionJWT::isSignedIn(trim($token));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->select('*')
          ->from('driver')
          ->where('user_id', $tokenData['payloadData']['id'])
          ->get()
          ->result()
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

  public function insertOne() {
    try {
      $token = $_POST['token'];
      $tokenData = SessionJWT::isSignedIn(trim($token));

      $_POST['user_id'] = $tokenData['payloadData']['id'];
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'DInsertOne'
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->insert('driver', array(
          'user_id' => $tokenData['payloadData']['id'],
          'first_name' => trim($_POST['firstName']),
          'last_name' => trim($_POST['lastName'])
        ))
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

  public function getById() {
    try {
      $token = $_POST['token'];
      $tokenData = SessionJWT::isSignedIn(trim($token));

      $_POST['user_id'] = $tokenData['payloadData']['id'];
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'DGetById'
      ));

      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->selectDataById($_POST['id'], $_POST['user_id'])
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

  public function editOne() {
    try {
      $token = $_POST['token'];
      $tokenData = SessionJWT::isSignedIn(trim($token));

      $_POST['user_id'] = $tokenData['payloadData']['id'];
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'DEditOne'
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->where(array(
            'id' => $_POST['id'],
            'user_id' => $_POST['user_id']
          ))
          ->update('driver', array(
            'first_name' => trim($_POST['firstName']),
            'last_name' => trim($_POST['lastName'])
          ))
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

  public function deleteOne() {
    try {
      $token = $_POST['token'];
      $tokenData = SessionJWT::isSignedIn(trim($token));

      $_POST['user_id'] = $tokenData['payloadData']['id'];
      Validation::validate(array(
        'data' => $_POST,
        'stringConfig' => 'DDeleteOne'
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->where(array(
            'id' => $_POST['id'],
            'user_id' => $_POST['user_id']
          ))
          ->delete('driver')
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

  public function selectDataById($id, $userId) {
    return $this->db->select('
        id AS ID,
        first_name AS firstName,
        last_name AS lastName
      ')
      ->from('driver')
      ->where(array(
        'id' => $id,
        'user_id' => $userId
      ))
      ->get()
      ->row();
  }
}
