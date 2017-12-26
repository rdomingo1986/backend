<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CORS {

  public static function checkCORS() {
    $CI =& get_instance();

    $CI->load->config('cors');
    $CORSHeaders = $CI->config->item('CORS_headers');
    $CORSMethods = $CI->config->item('CORS_methods');
    $CORSAnyDomain = $CI->config->item('CORS_anydomain');
    $CORSOrigins = $CI->config->item('CORS_origins');

    $allowedHeaders = implode(', ', $CORSHeaders);
    $allowedMethods = implode(', ', $CORSMethods);

    if($CORSAnyDomain === true) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: '.$allowedHeaders);
        header('Access-Control-Allow-Methods: '.$allowedMethods);
    } else {
      $origin = $CI->input->server('HTTP_ORIGIN');
      if ($origin === NULL) {
        $origin = '';
      }
      if(in_array($origin, $CORSOrigins)) {
        header('Access-Control-Allow-Origin: '.$origin);
        header('Access-Control-Allow-Headers: '.$allowedHeaders);
        header('Access-Control-Allow-Methods: '.$allowedMethods);
      } else {
        // exit(0);
      }
    }
    if ($CI->input->method() === 'options') {
      exit;
    }
  }
}