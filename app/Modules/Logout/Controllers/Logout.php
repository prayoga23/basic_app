<?php

namespace Modules\Logout\Controllers;

use App\Controllers\BaseController;

class Logout extends BaseController
{
  public function index()
  {
    session_destroy();
    return redirect()->to('/');
  }
}
