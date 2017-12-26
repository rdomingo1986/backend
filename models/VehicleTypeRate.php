<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleTypeRate extends CI_Model {

  public function selectDataById($id, $userId) {
    return $this->db->select('*')
      ->from('vehicletype_rate')
      ->where(array(
        'id' => $id,
        'user_id' => $userId
      ))
      ->get()
      ->result();
  }
}
