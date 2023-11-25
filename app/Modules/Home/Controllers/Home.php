<?php

namespace Modules\Home\Controllers;

use App\Controllers\SimapController;
use Modules\Additional_setting\Controllers\Additional_setting;
use Modules\Setting\Controllers\Setting;

class Home extends SimapController
{
  private function get_module()
  {
    return 'Home';
  }

  public function get_title()
  {
    return 'Dashboard';
  }

  public function index()
  {
    // Change value of string data below according to implemented module
    $data['module'] = $this->get_module();
    $data['view_file'] = 'home_view';
    $data['title'] = 'Aplikasi Simap';

    $this->template->run($data);
  }
}
