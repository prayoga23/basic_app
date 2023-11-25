<?php

namespace Modules\Credential\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\URI;
use Modules\Database\Controllers\Database_controller;

class Credential extends BaseController
{
  protected $uri;
  protected $custom_db;

  public function __construct()
  {
    $this->uri = new URI(current_url());
    $this->custom_db = new Database_controller();
  }

  public function has_access($page, $title)
  {
    if (session()->get('simap_user_id') != 1) {
      $update_funtions = array('add', 'edit', 'adjust', 'return', 'pay');
      $delete_funtions = array('delete');
      $active_module_function = explode('_', $this->uri->getSegment(2));

      $user = $this->custom_db->get(array(
        'table' => 'user',
        'where' => array('id' => session()->get('simap_user_id'))
      ))->getRowArray();

      $pages = explode(',', $user['menu_access']);
      $verified_pages = array();
      $user_access = array();

      // Split module and access
      foreach ($pages as $current_page)
        list($verified_pages_array[], $user_access[]) = explode('.', $current_page);

      $is_user_has_access = false;
      foreach ($verified_pages_array as $key => $new_page) {
        if ($new_page == $page) {
          $is_user_has_access = true;
          // Check readonly / update access
          $access = intval($user_access[$key]);

          if (array_intersect($active_module_function, $update_funtions) && !$access) {
            session()->setFlashdata('error_message', "Anda hanya memiliki akses lihat di halaman $title");
            return redirect()->to(base_url($page));
          } else if (array_intersect($active_module_function, $delete_funtions) && ($access == 1)) {
            session()->setFlashdata('error_message', "Anda tidak memiliki akses hapus di halaman $title");
            return redirect()->to(base_url($page));
          }

          return $access;
        }
      }

      if (!$is_user_has_access) {
        session()->setFlashdata('error_message', "Anda tidak memiliki akses ke halaman $title");
        return redirect()->to(base_url());
      }
    } else
      return true;
  }

  public function get_valid_user()
  {
    $pass = $this->request->getPost('pass');
    $data['id'] = '';

    $condition = array('password' => $pass, 'is_sales_approval' => true);
    $db_data = $this->custom_db->get(array(
      'table' => 'user',
      'where' => $condition,
      'field' => array('id')
    ));

    if (!empty($db_data)) {
      $data = $db_data->getRowArray();
    }

    echo json_encode($data);
  }
}
