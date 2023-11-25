<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\URI;
use Modules\Database\Controllers\Database_controller;

class Auth_filter implements FilterInterface
{

  public function before(RequestInterface $request, $arguments = null)
  {
    $custom_db = new Database_controller();
    $uri = new URI(current_url());

    // is user logged in?
    if (!session()->get('simap_logged_in')) {
      // get current url if it is not home url to add to callback param in login page
      if (current_url() != base_url() . '/') {
        $callback_url = urlencode(current_url());
        session()->setFlashdata('error_message', 'Anda belum login');
        return redirect()->to(base_url("login?callback=$callback_url"));
      } else {
        return redirect()->to(base_url("login"));
      }
    }

    // did user change password?
    $new_user = $custom_db->get(array(
      'table' => 'user',
      'where' => array(
        'id' => session()->get('simap_user_id'),
        'is_new_password' => true
      )
    ));
    if (empty($new_user) && ($uri->getSegment(1) != 'user_management')) {
      session()->setFlashdata('error_message', 'Silahkan ganti Sandi untuk akses pertama');
      return redirect()->to(base_url('user_management/change_password'));
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
  }
}
