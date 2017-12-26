<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login {

  public static function userSignIn($parameters) {
    $CI =& get_instance();
    
    $CI->load->config('login_process');
    $LOGINTable = $CI->config->item('LOGIN_table');
    $LOGINId = $CI->config->item('LOGIN_id');
    $LOGINAlias = $CI->config->item('LOGIN_alias');
    $LOGINPassword = $CI->config->item('LOGIN_password');
    $LOGINPayloadToken = $CI->config->item('LOGIN_payloadtoken');
    $LOGINEncrypMode = $CI->config->item('LOGIN_encrypmode');
    $LOGINStateType = $CI->config->item('LOGIN_statetype');
    $LOGINLastLoginTime = $CI->config->item('LOGIN_lastlogintime');
    $LOGINLastLoginIp = $CI->config->item('LOGIN_lastloginip');

    $CI->load->config('sessionjwt');
    $JWTTable = $CI->config->item('JWT_table');
    $JWTString = $CI->config->item('JWT_string');
    $JWTTimestamp = $CI->config->item('JWT_timestamp');
    $JWTIP = $CI->config->item('JWT_ip');
    $JWTDuration = $CI->config->item('JWT_duration');
    
    $selectStatement = '';
    foreach($LOGINPayloadToken AS $field) {
      $selectStatement .= $field.',';
    }
    $selectStatement = substr($selectStatement, 0, -1);

    if($LOGINEncrypMode == 'CIEncryption') {
      $userData = $CI->db->select($LOGINPassword.','.$selectStatement)
        ->from($LOGINTable)
        ->where($LOGINAlias, $parameters['login'])
        ->get()
        ->row_array();

      if(isset($userData)) {
        if($CI->encryption->decrypt($userData[$LOGINPassword]) != $parameters['password']) {
          throw New Exception('WRONGLOGINORPASSWORD');
        } else {
          $payloadData = array();
          foreach($LOGINPayloadToken AS $field) {
            $payloadData[$field] = $userData[$field];
          }
          if($LOGINStateType == 'JWT') {
            $payloadData['duration'] = $JWTDuration;
            
            do {
              $payloadData['timestamp'] = time();
              $token = SessionJWT::generateToken($payloadData);
              $tokenExists = SessionJWT::tokenExists($token);
            } while($tokenExists);
            
            SessionJWT::registerToken($token, $payloadData['timestamp']);

            $CI->db->where($LOGINAlias, $parameters['login'])
              ->update($LOGINTable, array(
                $LOGINLastLoginTime => date('Y-m-d H:i:s', time()),
                $LOGINLastLoginIp => $_SERVER['REMOTE_ADDR']
              ));
  
            return array(
              'token' => $token,
              'payloadData' => $payloadData 
            );
          } else if($LOGINStateType == 'CISessions') {
            //desarrollar
          } else {
            throw New Exception('Tipo de estado no soportado. Login Helper');
          }
        }
      } else {
        throw New Exception('WRONGLOGINORPASSWORD');
      }
    } else if($LOGINEncrypMode == 'MD5'){
      //desarrollar
    } else {
      throw New Exception('Tipo de encriptación no soportado. Login Helper');
    }
  }

  public static function userSignUp($parameters) {
    $CI =& get_instance();

    $CI->load->config('login_process');
    $LOGINTable = $CI->config->item('LOGIN_table');
    $LOGINAlias = $CI->config->item('LOGIN_alias');
    $LOGINPassword = $CI->config->item('LOGIN_password');
    $LOGINEncrypMode = $CI->config->item('LOGIN_encrypmode');
    $LOGINCreatedAt = $CI->config->item('LOGIN_createdat');

    if($LOGINEncrypMode == 'CIEncryption') {
      $password = $CI->encryption->encrypt($parameters['password']);
    } else if($LOGINEncrypMode == 'MD5'){
      $password = md5($parameters['password']);
    } else {
      throw New Exception('Tipo de encriptación no soportado. Login Helper');
    }

    return $CI->db->insert($LOGINTable, array(
      $LOGINAlias => $parameters['login'],
      $LOGINPassword => $password,
      $LOGINCreatedAt  => date('Y-m-d H:i:s', time())
    ));
  }

  public static function userChangePassword($parameters) {
    $CI =& get_instance();

    $CI->load->config('login_process');
    $LOGINTable = $CI->config->item('LOGIN_table');
    $LOGINId = $CI->config->item('LOGIN_id');
    $LOGINAlias = $CI->config->item('LOGIN_alias');
    $LOGINPassword = $CI->config->item('LOGIN_password');
    $LOGINEncrypMode = $CI->config->item('LOGIN_encrypmode');
    
    $isRecovery = isset($parameters['recoveryHash']);

    if($isRecovery) {
      $process = Validation::isRecoveryHashValid($parameters['recoveryHash']);
    } else {
      $process = Validation::isCurrentPassword(array(
        'id' => $parameters['userId'],
        'password' => $parameters['oldPassword']
      ));
    }

    if(!$process) {
      if($isRecovery) {
        throw new Exception('INVALIDOREXPIREDHASH');
      } else {
        throw new Exception('INVALIDCURRENTPASSWORD');
      }
    } else {
      if($isRecovery) {
        return $CI->db->where($LOGINId, $process)
          ->update($LOGINTable, array(
            $LOGINPassword => $CI->encryption->encrypt($parameters['password']),
            'passreset_code' => NULL,
            'last_req_passreset' => NULL
          ));
      } else {
        return $CI->db->where($LOGINId, $parameters['userId'])
          ->update($LOGINTable, array(
            $LOGINPassword => $CI->encryption->encrypt($parameters['password']),
            'passreset_code' => NULL,
            'last_req_passreset' => NULL,
            'account_status' => 'validated'
          ));
      }
    }
  }

  public static function saveRecoveryPasswordCode($parameters) {
    $CI =& get_instance();

    $CI->load->config('login_process');
    $LOGINTable = $CI->config->item('LOGIN_table');
    $LOGINAlias = $CI->config->item('LOGIN_alias');

    Login::loginExists($parameters['login']);
    Login::loginIsValidated($parameters['login']);

    return $CI->db->where($LOGINAlias, $parameters['login'])
      ->update($LOGINTable, array(
        'passreset_code' => $parameters['recoveryCode'],
        'last_req_passreset' => date('Y-m-d H:i:s', time())
      ));
  }

  public static function loginExists($login) {
    $CI =& get_instance();

    $CI->load->config('login_process');
    $LOGINTable = $CI->config->item('LOGIN_table');
    $LOGINAlias = $CI->config->item('LOGIN_alias');

    $res = $CI->db->select($LOGINAlias)
      ->from($LOGINTable)
      ->where($LOGINAlias, $login)
      ->get()
      ->num_rows();

    if($res === 0) {
      throw new Exception('LOGINNOTEXISTS');
    }
    return true;
  }

  public static function loginIsValidated($login) {
    $CI =& get_instance();

    $CI->load->config('login_process');
    $LOGINTable = $CI->config->item('LOGIN_table');
    $LOGINAlias = $CI->config->item('LOGIN_alias');

    $res = $CI->db->select('account_status')
      ->from($LOGINTable)
      ->where(array(
        $LOGINAlias => $login,
        'account_status' => 'validated'
      ))
      ->get()
      ->num_rows();

    if($res === 0) {
      throw new Exception('ACCOUNTISNOTVALIDATED');
    }
    return true;
  }

  public static function getLoginFieldId() {
    $CI =& get_instance();
    $CI->load->config('login_process');

    foreach($CI->config->item('selectStatement') AS $field) {
      if(is_array($field)) {
        if($field[0] == $CI->config->item('fieldID')) {
          return $field[1];
        }
      } else {
        if($field == $CI->config->item('fieldID')) {
          return $field;
        }
      }
    }
  }

  public static function userSignOut($token) {
    $CI = & get_instance();

    $CI->load->config('sessionjwt');
    $JWTTable = $CI->config->item('JWT_table');
    $JWTString = $CI->config->item('JWT_string');
    $JWTTimestamp = $CI->config->item('JWT_timestamp');
    $JWTIP = $CI->config->item('JWT_ip');
    $JWTDuration = $CI->config->item('JWT_duration');
    
    return $CI->db->where($JWTString, $token)
      ->delete($JWTTable);
  }
}