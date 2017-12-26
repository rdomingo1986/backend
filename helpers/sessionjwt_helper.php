<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SessionJWT {

  public static function generateToken($payload) {
    $CI =& get_instance();
    $CI->load->helper('jwt');
    return JWT::encode($payload, $CI->config->item('encryption_key'));
  }

  public static function isSignedIn($token) {
    $CI =& get_instance();
    
    $CI->load->config('login_process');
    $LOGINStateType = $CI->config->item('LOGIN_statetype');

    if($LOGINStateType == 'JWT') { 
      try {
        return SessionJWT::regenerateToken($token);
      } catch (Exception $e) {
        throw new Exception('INVALIDTOKEN');
      }
    } else if($CI->config->item('stateType') == 'CISessions') {
      //desarrollar
    }
  }

  public static function regenerateToken($token) {
    $CI =& get_instance();

    $CI->load->config('sessionjwt');
    $JWTTable = $CI->config->item('JWT_table');
    $JWTId = $CI->config->item('JWT_id');
    $JWTString = $CI->config->item('JWT_string');
    $JWTTimestamp = $CI->config->item('JWT_timestamp');
    $JWTIP = $CI->config->item('JWT_ip');
    $JWTDuration = $CI->config->item('JWT_duration');

    $tokenExists = SessionJWT::tokenExists($token);

    if(!$tokenExists) {
      throw new Exception('TOKENUNREGISTERED');
    }
    
    $dataToken = SessionJWT::decomposeToken($token);
    
    if((time() - $dataToken['timestamp']) >= $JWTDuration) {
      SessionJWT::deleteToken($token);
      $payloadData = array();
      foreach($dataToken AS $key => $value) {
        $payloadData[$key] = $value;
      }
      $payloadData['duration'] = $JWTDuration;

      do {
        $payloadData['timestamp'] = time();
        $token = SessionJWT::generateToken($payloadData);
        $tokenExists = SessionJWT::tokenExists($token);
      } while($tokenExists);

      SessionJWT::registerToken($token, $payloadData['timestamp']);
    }

    return array(
      'token' => $token,
      'payloadData' => !isset($payloadData) ? $dataToken : $payloadData
    );
  }

  public static function decomposeToken($token) {
    $CI =& get_instance();
    $CI->load->helper('jwt');
    return (array) JWT::decode($token, $CI->config->item('encryption_key'));
  }

  public static function registerToken($token, $timestamp) {
    $CI =& get_instance();
    
    $CI->load->config('sessionjwt');
    $JWTTable = $CI->config->item('JWT_table');
    $JWTString = $CI->config->item('JWT_string');
    $JWTTimestamp = $CI->config->item('JWT_timestamp');
    $JWTIP = $CI->config->item('JWT_ip');

    return $CI->db->insert($JWTTable, array(
      $JWTString => $token,
      $JWTTimestamp => $timestamp,
      $JWTIP => $_SERVER['REMOTE_ADDR']
    ));
  }

  public static function tokenExists($token) {
    $CI =& get_instance();
    
    $CI->load->config('sessionjwt');
    $JWTTable = $CI->config->item('JWT_table');
    $JWTId = $CI->config->item('JWT_id');
    $JWTString = $CI->config->item('JWT_string');

    $tokenResult = $CI->db->select($JWTId)
      ->from($JWTTable)
      ->where($JWTString, $token)
      ->get();

    return $tokenResult->num_rows() === 0 ? false : true;
  }

  public static function deleteToken($token) {
    $CI =& get_instance();

    $CI->load->config('sessionjwt');
    $JWTTable = $CI->config->item('JWT_table');
    $JWTString = $CI->config->item('JWT_string');

    return $CI->db->where($JWTString, $token)
      ->delete($JWTTable);
  }
}