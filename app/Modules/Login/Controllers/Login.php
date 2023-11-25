<?php

namespace Modules\Login\Controllers;

use App\Controllers\BaseController;
use Modules\Database\Controllers\Database_controller;

class Login extends BaseController
{
  protected $custom_db;

  public function __construct()
  {
    $this->custom_db = new Database_controller();
  }

  private function get_table_name()
  {
    return 'user';
  }

  private function get_module()
  {
    return 'login';
  }

  private function get_view_path($filename)
  {
    return "\Modules\Login\Views\\$filename";
  }

  public function index($data = null)
  {
    if (session()->get('simap_logged_in'))
      return redirect()->to('/');
    else {
      $shift_db = $this->get_shift_db();
      $data['shift_id'] = $this->get_estimated_shift($shift_db);
      $data['shift'] = $this->get_shift($shift_db);
      $data['module'] = $this->get_module();
      $data['view_file'] = 'login_view';
      $data['title'] = 'LOGIN';
      $data['callback_url'] = esc($this->request->getGet('callback'));

      echo view($this->get_view_path($data['view_file']), $data);
    }
  }

  public function validate_login()
  {
    $callback_url = esc($this->request->getPost('callback_url')) ?? '';
    $rules = array(
      'uname' => array('label' => 'Username', 'rules' => 'required'),
      'pass'  => array('label' => 'Password', 'rules' => 'required'),
    );

    if ($this->validate($rules) == FALSE) {
      $this->index(['callback_url' => $callback_url, 'validation' => $this->validator]);
    } else {
      $username = strtoupper(esc($this->request->getPost('uname')));
      $password = strtoupper(esc($this->request->getPost('pass')));

      $shift_id = esc($this->request->getPost('shift_id'));
      $shift_db = $this->get_shift_db();
      $shift = $this->get_shift($shift_db);
      $shift = $shift[$shift_id];

      $user = $this->custom_db->get(array(
        'table' => $this->get_table_name(),
        'where' => array(
          'username' => $username,
          'password' => md5($password)
        )
      ));

      if (!empty($user)) {
        $user = $user->getRowArray();
        $url = urldecode($callback_url);

        if ($user['is_super_admin'] || !empty($shift)) {
          if ($user['is_active']) {
            $this->custom_db->_update(
              $this->get_table_name(),
              array('last_login' => date('Y-m-d H:i:s')),
              array('id' => $user['id']),
              false,
              true
            );
            //TODO: Modules::run('sync_central/fetch');
            session()->set(array(
              'simap_user' => $user['name'],
              'simap_user_id' => $user['id'],
              'simap_is_due_date_notification' => $user['is_due_date_notification'],
              'simap_logged_in' => true,
              'simap_shift_id' => $shift_id,
              'simap_shift' => $shift,
              'simap_is_credit_sales_notification' => $user['is_credit_sales_notification'],
              'simap_is_price_verification_notification' => $user['is_price_verification_notification'],
              'simap_has_access_dashboard' => $user['has_access_dashboard'],
              'simap_tb_px' => $user['table_prefix'],
              'simap_is_su' => $user['is_super_admin']
            ));
          } else {
            session()->setFlashdata('error_message', 'Akun sudah dinonaktifkan, hubungi Administrator SIMAP');
            $url = base_url($this->get_module());
          }
        } else {
          session()->setFlashdata('error_message', 'Hubungi Administrator untuk menambahkan Jam Kerja');
          $url = base_url($this->get_module());
        }
        return redirect()->to($url);
      } else {
        session()->setFlashdata('error_message', 'Username atau Password salah');
        return redirect()->to(base_url($this->get_module() . ($callback_url ? "?callback=$callback_url" : '')));
      }
    }
  }

  private function get_local_printer()
  {
    $data = $this->custom_db->get(array(
      'table' => 'printer_list',
      'where' => array(
        'client_ip' => $this->request->getIPAddress(),
        'is_allowed' => true
      )
    ));

    if (!empty($data)) {
      $data = $data->getRowArray();
      return $data['printer_name'];
    }

    return false;
  }

  private function get_shift_db()
  {
    return $this->custom_db->get(array(
      'table' => 'shift a',
      'orderby' => 'start_time, end_time'
    ));
  }

  private function get_shift($shift_db)
  {
    $shift_data = array();
    if (!empty($shift_db)) {
      foreach ($shift_db->getResult() as $row) {
        $shift_data[$row->id] = $this->two_digit($row->start_time) . ' - ' . $this->two_digit($row->end_time);
      }
    }
    return $shift_data;
  }

  private function get_estimated_shift($shift_db)
  {
    if (!empty($shift_db)) {
      $current_hour = date('H');
      foreach ($shift_db->getResult() as $row) {
        if ($row->start_time > $row->end_time) {
          if (($current_hour >= $row->start_time) || $current_hour < $row->end_time)
            return $row->id;
        } else {
          if ($current_hour >= $row->start_time) {
            if ($current_hour < $row->end_time)
              return $row->id;
            else if (($row->end_time - $row->start_time) < 0) {
              return $row->id;
            }
          }
        }
      }
    }
    return false;
  }

  private function two_digit($val)
  {
    return ((strlen($val) <= 1) ? '0' : '') . $val . '.00';
  }
}
