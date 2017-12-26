<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends CI_Model {

  public function listAll() {
    try {
      $token = $_POST['token'];
      $tokenData = SessionJWT::isSignedIn(trim($token));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->select('*')
          ->from('vehicle')
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
        'stringConfig' => 'VInsertOne'
      ));

      Validation::isUniqueField(array(
        'databaseField' => 'shield',
        'databaseTable' => 'vehicle',
        'fieldValue' => trim($_POST['shield']),
        'databaseOwnerField' => 'user_id',
        'fieldOwneValue' => $_POST['user_id']
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->insert('vehicle', array(
          'user_id' => $tokenData['payloadData']['id'],
          'vehicletype_id' => strlen(trim($_POST['vehicletype'])) === 0 ? NULL : $_POST['vehicletype'],
          'brand' => trim($_POST['brand']),
          'model' => trim($_POST['model']),
          'shield' => trim($_POST['shield']),
          'year' => trim($_POST['year'])
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
        'stringConfig' => 'VGetById'
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
        'stringConfig' => 'VEditOne'
      ));

      Validation::isUniqueField(array(
        'databaseField' => 'shield',
        'databaseTable' => 'vehicle',
        'databaseFieldId' => 'id',
        'fieldIdValue' => $_POST['id'],
        'fieldValue' => trim($_POST['shield']),
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
          ->update('vehicle', array(
            'vehicletype_id' => strlen(trim($_POST['vehicletype'])) === 0 ? NULL : $_POST['vehicletype'],
            'brand' => trim($_POST['brand']),
            'model' => trim($_POST['model']),
            'shield' => trim($_POST['shield']),
            'year' => trim($_POST['year'])
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
        'stringConfig' => 'VDeleteOne'
      ));
      
      return array(
        'success' => true,
        'token' => $tokenData['token'],
        'responseData' => $this->db->where(array(
            'id' => $_POST['id'],
            'user_id' => $_POST['user_id']
          ))
          ->delete('vehicle')
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
        vehicle_id AS vehicleId,
        brand AS brand,
        model AS model,
        year AS year,
        shield AS shield
      ')
      ->from('vehicle')
      ->where(array(
        'id' => $id,
        'user_id' => $userId
      ))
      ->get()
      ->row();
  }
}
