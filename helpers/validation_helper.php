<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validation {

  public static function validate($parameters) {
    $CI =& get_instance();
    $CI->load->library('form_validation');

    $CI->form_validation->set_data($parameters['data']);
    if(!isset($parameters['stringConfig']) || $parameters['stringConfig'] == '') {
      $CI->form_validation->set_rules($parameters['rules']);
    }
    $result = $CI->form_validation->run($parameters['stringConfig']);
    if($result === false) {
      throw new Exception(implode(',',$CI->form_validation->error_array()));
      exit;
    }
    return true;
  }

  public static function isUniqueField($parameters) {
    $CI =& get_instance();
    $CI->db->select('id')
      ->from($parameters['databaseTable'])
      ->where($parameters['databaseField'], $parameters['fieldValue']);
    if(isset($parameters['databaseOwnerField'])) {
      $CI->db->where($parameters['databaseOwnerField'], $parameters['fieldOwneValue']);
    }
    if(isset($parameters['databaseFieldId'])) {
      $CI->db->where($parameters['databaseFieldId'].' !=', $parameters['fieldIdValue']);
    }
    $numRows = $CI->db->get()
      ->num_rows();
    
    if($numRows != 0) {
      throw new Exception('FIELDISNOTUNIQUE');
      exit;
    }
    return true;
  }

  public static function isCurrentPassword($parameters) {
    $CI =& get_instance();

    $CI->load->config('login_process');
    $LOGINTable = $CI->config->item('LOGIN_table');
    $LOGINId = $CI->config->item('LOGIN_id');
    $LOGINPassword = $CI->config->item('LOGIN_password');
    
    $userData = $CI->db->select($LOGINPassword)
      ->from($LOGINTable)
      ->where($LOGINId, $parameters['id'])
      ->get()
      ->row_array();

    if(isset($userData) && $CI->encryption->decrypt($userData[$LOGINPassword]) == $parameters['password']) {
      return true;
    } else {
      return false;
    }
  }

  public static function isRecoveryHashValid($hash, $interval = '600') {
    $CI =& get_instance();

    $res = $CI->db->select('id')
      ->from('user')
      ->where('passreset_code', $hash)
      ->where('(last_req_passreset + INTERVAL ' . $interval . ' SECOND) >', date('Y-m-d H:i:s', time()))
      ->get();

    return $res->num_rows() === 0 ? false : $res->row()->id;
  }
}