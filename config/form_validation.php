<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['validateToken'] = array(
  array(
    'field' => 'token',
    'label' => 'token',
    'rules' => 'trim|required'
  ),
);

$config['signin'] = array(
  array(
    'field' => 'email',
    'label' => 'email',
    'rules' => 'trim|required|max_length[255]|valid_email'
  ),
  array(
    'field' => 'password',
    'label' => 'password',
    'rules' => 'required|min_length[8]|max_length[20]'
  )
);

$config['signup'] = array(
  array(
    'field' => 'email',
    'label' => 'email',
    'rules' => 'required|trim|max_length[255]|valid_email'
  )
);

$config['changeTemporalPassword'] = array(
  array(
    'field' => 'oldPassword',
    'label' => 'oldPassword',
    'rules' => 'required|regex_match[/^[a-f0-9]{8}$/i]'
  ),
  array(
    'field' => 'password',
    'label' => 'password',
    'rules' => 'required|min_length[8]|max_length[20]'
  ),
  array(
    'field' => 'passwordRepeat',
    'label' => 'passwordRepeat',
    'rules' => 'required|min_length[8]|max_length[20]|matches[password]'
  )
);

$config['changePassword'] = array(
  array(
    'field' => 'oldPassword',
    'label' => 'oldPassword',
    'rules' => 'required|min_length[8]|max_length[20]'
  ),
  array(
    'field' => 'password',
    'label' => 'password',
    'rules' => 'required|min_length[8]|max_length[20]'
  ),
  array(
    'field' => 'passwordRepeat',
    'label' => 'passwordRepeat',
    'rules' => 'required|min_length[8]|max_length[20]|matches[password]'
  )
);

$config['recoveryPassword'] = array(
  array(
    'field' => 'recoveryHash',
    'label' => 'recoveryHash',
    'rules' => 'required|regex_match[/^[a-f0-9]{32}$/i]'
  ),
  array(
    'field' => 'password',
    'label' => 'password',
    'rules' => 'required|min_length[8]|max_length[20]'
  ),
  array(
    'field' => 'passwordRepeat',
    'label' => 'passwordRepeat',
    'rules' => 'required|min_length[8]|max_length[20]|matches[password]'
  )
);

$config['requestRecoveryPassword'] = array(
  array(
    'field' => 'email',
    'label' => 'email',
    'rules' => 'required|trim|max_length[255]|valid_email'
  )
);

$config['VTInsertOne'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'name',
    'label' => 'name',
    'rules' => 'trim|required|max_length[150]'
  ),
  array(
    'field' => 'description',
    'label' => 'description',
    'rules' => 'trim|max_length[255]'
  )
);

$config['VTDeleteOne'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  )
);

$config['VTGetById'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  )
);

$config['VTEditOne'] = array(
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'name',
    'label' => 'name',
    'rules' => 'trim|required|max_length[150]'
  ),
  array(
    'field' => 'description',
    'label' => 'description',
    'rules' => 'trim|max_length[255]'
  )
);

$config['TInsertOne'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'name',
    'label' => 'name',
    'rules' => 'trim|required|max_length[100]'
  ),
  array(
    'field' => 'description',
    'label' => 'description',
    'rules' => 'trim|max_length[255]'
  ),
  array(
    'field' => 'amount',
    'label' => 'amount',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'operation',
    'label' => 'operation',
    'rules' => 'required|in_list[add,substract]'
  )
);

$config['TGetById'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  )
);

$config['TEditOne'] = array(
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'name',
    'label' => 'name',
    'rules' => 'trim|required|max_length[100]'
  ),
  array(
    'field' => 'description',
    'label' => 'description',
    'rules' => 'trim|max_length[255]'
  ),
  array(
    'field' => 'amount',
    'label' => 'amount',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'operation',
    'label' => 'operation',
    'rules' => 'required|in_list[add,substract]'
  )
);

$config['TDeleteOne'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  )
);

$config['DInsertOne'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'firstName',
    'label' => 'firstName',
    'rules' => 'trim|required|max_length[150]'
  ),
  array(
    'field' => 'lastName',
    'label' => 'lastName',
    'rules' => 'trim|required|max_length[150]'
  )
);

$config['DGetById'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  )
);

$config['DEditOne'] = array(
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'firstName',
    'label' => 'firstName',
    'rules' => 'trim|required|max_length[150]'
  ),
  array(
    'field' => 'lastName',
    'label' => 'lastName',
    'rules' => 'trim|required|max_length[150]'
  )
);

$config['DDeleteOne'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  )
);

$config['VInsertOne'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'vehicletype',
    'label' => 'vehicletype',
    'rules' => 'numeric'
  ),
  array(
    'field' => 'brand',
    'label' => 'brand',
    'rules' => 'trim|required|max_length[150]'
  ),
  array(
    'field' => 'model',
    'label' => 'model',
    'rules' => 'trim|required|max_length[150]'
  ),
  array(
    'field' => 'shield',
    'label' => 'shield',
    'rules' => 'trim|required|max_length[50]'
  ),
  array(
    'field' => 'year',
    'label' => 'year',
    'rules' => 'trim|required|numeric|max_length[4]'
  )
);

$config['VGetById'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  )
);

$config['VEditOne'] = array(
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'vehicletype',
    'label' => 'vehicletype',
    'rules' => 'numeric'
  ),
  array(
    'field' => 'brand',
    'label' => 'brand',
    'rules' => 'trim|required|max_length[150]'
  ),
  array(
    'field' => 'model',
    'label' => 'model',
    'rules' => 'trim|required|max_length[150]'
  ),
  array(
    'field' => 'shield',
    'label' => 'shield',
    'rules' => 'trim|required|max_length[50]'
  ),
  array(
    'field' => 'year',
    'label' => 'year',
    'rules' => 'trim|required|numeric|max_length[4]'
  )
);

$config['VDeleteOne'] = array(
  array(
    'field' => 'user_id',
    'label' => 'user_id',
    'rules' => 'required|numeric'
  ),
  array(
    'field' => 'id',
    'label' => 'id',
    'rules' => 'required|numeric'
  )
);