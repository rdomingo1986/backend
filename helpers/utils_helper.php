<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utils {

  public static function randomMd5Hash($parameters = []) {
    if(!array_key_exists('databaseTable', $parameters) && !array_key_exists('databaseField', $parameters)) {
      return md5(md5(time() . time() . time()));
      exit;
    }
    $CI =& get_instance();
    do {
      $md5Hash = md5(time() . time() . time());
      $res = $CI->db->select($parameters['databaseFieldID'])
        ->from($parameters['databaseTable'])
        ->where($parameters['databaseField'], $md5Hash)
        ->get()
        ->num_rows();
    }while($res != 0);
    return $md5Hash;
  }

  public static function isUniqueValue($parameters = []) {
    $CI =& get_instance();
    
    $CI->db->select('id')
      ->from($parameters['table'])
      ->where($parameters['column'], $parameters['value']);

    if(array_key_exists('owner_id', $parameters) && $parameters['owner_id'] != false) {
      $CI->db->where($parameters['column_owner'], $parameters['owner_id']);
    }

    if(array_key_exists('id', $parameters) && $parameters['id'] != false) {
      $CI->db->where('id !=', $parameters['id']);
    }

    $res = $CI->db->get()
      ->num_rows();
    
    if($res != 0) {
      if(array_key_exists('ajax-validation', $parameters) && $parameters['ajax-validation']) {
        return array_key_exists('invert', $parameters) && $parameters['invert'] ? true : false;
      }
      throw new Exception('El campo '.$parameters['column'].' con el valor '.$parameters['value'].' ya se encuentra registrado');
    }
    return array_key_exists('invert', $parameters) && $parameters['invert'] ? false : true;
  }
}