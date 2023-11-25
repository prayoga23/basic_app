<?php

namespace Modules\Page_filter\Controllers;

use App\Controllers\BaseController;
use Modules\Database\Controllers\Database_controller;

class Page_filter extends BaseController
{
  protected $request;

  public function __construct()
  {
    $this->request = \Config\Services::request();
  }

  /*
   * Dynamically pass filter to be arranged in ref-ed array
   * 
   * @params array, array
   * @return void
   */
  public function set_filter_params(
    $data,
    $foreign_filter   = array(),
    $date_filter      = array(),
    $is_searching     = false,
    $table_alias      = '',
    $is_escape_string = false
  ) {
    $current_foreign_filter = [];

    foreach ($foreign_filter as $row) {
      $current_foreign_filter[] = array(
        'display_name'  => $row['display_name'],
        'name'          => $row['name'],
        'value'         => $this->request->getGet($row['name']),
        'foreign_table' => (isset($row['foreign_table']) ? $row['foreign_table'] : ''),
        'options'       => (isset($row['options']) ? $row['options'] : $this->get_dropdown_value($row['table_name'], $row['column_name'] ?? ''))
      );
    }

    $data['foreign_filter'] = $current_foreign_filter;

    $current_date_filter = array();

    foreach ($date_filter as $key => $row) {
      $foreign_table  = (isset($row['foreign_table']) ? $row['foreign_table'] : '');
      $foreign_name   = (empty($foreign_table) ? '' : ("{$foreign_table}_")) . $row['name'];

      if ($this->request->getGet($foreign_name)) {
        $data[$foreign_name] = array(
          0 => $this->request->getGet($foreign_name)[0]
        );

        if (!isset($row['to'])) {
          $data[$foreign_name][] = $this->request->getGet($foreign_name)[1];
        }
      }

      $current_date_filter[$key] = array(
        'display_name'  => $row['display_name'],
        'name'          => $foreign_name,
        'from'          => $data[$foreign_name][0] ?? '',
        'foreign_table' => $foreign_table,
        'foreign_name'  => $row['name']
      );

      if (!isset($row['to'])) {
        $current_date_filter[$key]['to'] = $data[$foreign_name][1] ?? '';
      }
    }
    $data['date_filter'] = $current_date_filter;

    if ($is_searching) {
      $current_condition_filter = array();
      $table_alias              = empty($table_alias) ? '' : ("{$table_alias}.");

      foreach ($current_foreign_filter as $filter) {
        $foreign_table = (empty($filter['foreign_table']) ? $table_alias : ("{$filter['foreign_table']}."));

        if ($filter['value'] != '') {
          $current_condition_filter["{$foreign_table}{$filter['name']}" . ($is_escape_string ? '=' : '')] = $filter['value'];
        }
      }

      foreach ($current_date_filter as $date) {
        $foreign_table = (empty($date['foreign_table']) ? $table_alias : ("{$date['foreign_table']}."));

        if (!isset($date['to']) && ($date['from'] != '')) {
          $current_condition_filter[$foreign_table . (isset($date['foreign_name']) ? $date['foreign_name'] : $date['name'])] = ($is_escape_string ? '"' : '') . $date['from'] . ($is_escape_string ? '"' : '');
        } else {
          if (($date['from'] != '') && ($date['to'] != '')) {
            $current_condition_filter[$foreign_table . (isset($date['foreign_name']) ? $date['foreign_name'] : $date['name']) . ' >='] = ($is_escape_string ? '"' : '') . $date['from'] . ($is_escape_string ? '"' : '');
            $current_condition_filter[$foreign_table . (isset($date['foreign_name']) ? $date['foreign_name'] : $date['name']) . ' <='] = ($is_escape_string ? '"' : '') . $date['to'] . ($is_escape_string ? '"' : '');
          }
        }
      }

      $data['condition_filter'] = $current_condition_filter;
    }

    return $data;
  }

  private function get_dropdown_value($table_name, $column_name)
  {
    $column_name = $column_name ?: 'id';

    $data = (new Database_controller)->get(array(
      'table'   => $table_name,
      'where'   => array('is_active' => 1),
      'orderby' => 'name'
    ));

    $array = array('' => '--Semua--');

    if (!empty($data)) {
      foreach ($data->getResult() as $row) {
        $array[$row->$column_name] = $row->name;
      }
    }

    return $array;
  }
}
