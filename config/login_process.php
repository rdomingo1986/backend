<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['LOGIN_table'] = 'user';
$config['LOGIN_id'] = 'id';
$config['LOGIN_alias'] = 'email';
$config['LOGIN_password'] = 'password';
$config['LOGIN_encrypmode'] = 'CIEncryption';
$config['LOGIN_statetype'] = 'JWT';
$config['LOGIN_lastlogintime'] = 'last_login';
$config['LOGIN_lastloginip'] = '	last_ip';
$config['LOGIN_createdat'] = '	created_at';
$config['LOGIN_payloadtoken'] = array('id', 'email', 'type');

