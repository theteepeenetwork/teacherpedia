<?php

namespace App\Validation;

use App\Models\Login_model;

class UserRules
{

  public function validateUser(string $str, string $fields, array $data)
  {
    $model = new Login_model();
    $user = $model->where('email', $data['user_email'])
      ->first();

    if (!$user)
      return false;

    return password_verify($data['user_password'], $user['password']);
  }

  public function validatePasswordChange(string $str, string $fields, array $data)
  {
    $model = new Login_model();
    $user = $model->where('email', session()->get('email'))
      ->first();

    if (!$user)
      return false;

    return password_verify($data['old_password'], $user['password']);
  }
}
