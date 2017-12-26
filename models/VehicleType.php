<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleType extends CI_Model {

  public function listAll() {
    try {
      $token = $_POST['token'];
      $tokenData = SessionJWT::isSignedIn(trim($token));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->select('*')
          ->from('vehicle_type')
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
        'stringConfig' => 'VTInsertOne'
      ));

      Validation::isUniqueField(array(
        'databaseField' => 'title',
        'databaseTable' => 'vehicle_type',
        'fieldValue' => trim($_POST['name']),
        'databaseOwnerField' => 'user_id',
        'fieldOwneValue' => $_POST['user_id']
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->insert('vehicle_type', array(
          'user_id' => $tokenData['payloadData']['id'],
          'title' => trim($_POST['name']),
          'description' => !isset($_POST['description']) ? NULL : trim($_POST['description'])
        ))
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'TOKENUNREGISTERED' || $e->getMessage() == 'INVALIDTOKEN' ) {
        return array(
          'errorCode' => 'NOSESSION',
          'success' => false
        );
      } else if($e->getMessage() == 'FIELDISNOTUNIQUE') {
        return array(
          'errorCode' => $e->getMessage(),
          'success' => false,
          'token' => $tokenData['token']
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
        'stringConfig' => 'VTGetById'
      ));

      $this->load->model('VehicleTypeRate', 'vehicletyperate');
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => array(
          $this->selectDataById($_POST['id'], $_POST['user_id']),
          $this->vehicletyperate->selectDataById($_POST['id'], $_POST['user_id'])
        )
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
        'stringConfig' => 'VTEditOne'
      ));

      Validation::isUniqueField(array(
        'databaseField' => 'title',
        'databaseTable' => 'vehicle_type',
        'databaseFieldId' => 'id',
        'fieldIdValue' => $_POST['id'],
        'fieldValue' => trim($_POST['name']),
        'databaseOwnerField' => 'user_id',
        'fieldOwneValue' => $_POST['user_id']
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->where(array(
            'id' => $_POST['id'],
            'user_id' => $_POST['user_id']
          ))
          ->update('vehicle_type', array(
            'title' => trim($_POST['name']),
            'description' => !isset($_POST['description']) ? NULL : trim($_POST['description'])
          ))
      );
    } catch (Exception $e) {
      if($e->getMessage() == 'TOKENUNREGISTERED' || $e->getMessage() == 'INVALIDTOKEN' ) {
        return array(
          'errorCode' => 'NOSESSION',
          'success' => false
        );
      } else if($e->getMessage() == 'FIELDISNOTUNIQUE') {
        return array(
          'errorCode' => $e->getMessage(),
          'success' => false,
          'token' => $tokenData['token']
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
        'stringConfig' => 'VTDeleteOne'
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->where(array(
            'id' => $_POST['id'],
            'user_id' => $_POST['user_id']
          ))
          ->delete('vehicle_type')
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
        title AS name,
        description AS description
      ')
      ->from('vehicle_type')
      ->where(array(
        'id' => $id,
        'user_id' => $userId
      ))
      ->get()
      ->row();
  }
}
