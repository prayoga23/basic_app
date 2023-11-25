<?php

namespace Modules\Template\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use Modules\Database\Controllers\Database_controller;
use Modules\Utility\Controllers\Utility;

class Template extends BaseController
{
  protected $request;
  private $custom_db;

  public function __construct(RequestInterface $request)
  {
    $this->request = $request;
    $this->custom_db = new Database_controller();
  }

  private function get_view_path($filename)
  {
    return "\Modules\Template\Views\\$filename";
  }

  public function run($data)
  {
    if ($data['module'] != 'preview') {
      $setting = $this->custom_db->get(array(
        'table' => 'setting'
      ))->getRowArray();

      $menu = $this->custom_db->get(array(
        'table' => 'user',
        'field' => array('html_access'),
        'where' => array('id' => session()->get('simap_user_id')),
        'limit' => 1,
        'offset' => 0
      ));

      if (!empty($menu)) {
        $current_menu = $menu->getRowArray();
        if (base_url() != $setting['base_url'])
          $current_menu['html_access'] = str_replace($setting['base_url'], base_url(), $current_menu['html_access']);
        $data['menu'] = $current_menu;
      }

      $data['is_active_session'] = (new Utility)->is_active_session();
      if (esc($this->request->getGet('keyword')))
        $data['keyword'] = esc($this->request->getGet('keyword'));
    }

    echo view($this->get_view_path('template_view'), $data);
  }
}
