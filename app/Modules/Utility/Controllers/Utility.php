<?php

namespace Modules\Utility\Controllers;

use App\Controllers\BaseController;
use Modules\Database\Controllers\Database_controller;

class Utility extends BaseController
{
  protected $request;
  protected $custom_db;

  public function __construct()
  {
    $this->request = \Config\Services::request();
    $this->custom_db = new Database_controller();
  }

  public function get_client_ip()
  {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

  public function get_previous_url($module)
  {
    return (isset($_SERVER['HTTP_REFERER'])
      ? (strpos($_SERVER['HTTP_REFERER'], base_url($module)) === false ? base_url() : $_SERVER['HTTP_REFERER'])
      : (base_url($module)));
  }

  public function is_active_session()
  {
    if (session()->get('simap_shift_id')) {
      $shift = $this->custom_db->get(array(
        'table' => 'shift',
        'where' => array('id' => session()->get('simap_shift_id')),
        'field' => array('start_time', 'end_time')
      ));

      if (!empty($shift)) {
        date_default_timezone_set(_timezone);
        $shift = $shift->getRowArray();
        $shift_start = date('Y-m-d') . ' ' . $shift['start_time'] . ':00:00';
        $shift_over = date('Y-m-d') . ' ' . $shift['end_time'] . ':00:00';

        if ($shift['start_time'] > $shift['end_time']) {
          if (intval(date('H')) > intval($shift['start_time'])) {
            $shift_over .= ' + 1 day';
          } else {
            $shift_start .= ' - 1 day';
          }
        }

        $shift_start = strtotime($shift_start);
        $shift_over = strtotime($shift_over);
        $current_time = strtotime(date('Y-m-d H:i:s'));

        if (($shift_over > $current_time) && ($shift_start < $current_time))
          if ($this->request->isAJAX()) echo true;
          else return true;
      } else {
        if ($this->request->isAJAX()) echo false;
        else return false;
      }
    } else {
      if ($this->request->isAJAX()) echo false;
      else return false;
    }
  }
}
