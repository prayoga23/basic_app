<?php

namespace Modules\Database\Controllers;

use App\Controllers\BaseController;
use Modules\Database\Models\Database_model;

class Database_controller extends BaseController
{
  protected $model;

  public function __construct()
  {
    $this->model = new Database_model();
  }

  private function get_date_type()
  {
    return array(
      'date',
      'datetime',
      'timestamp'
    );
  }

  public function index()
  {
    echo '1';
  }

  public function _insert($table_name, $data, $is_m_n_table = false)
  {
    $db = \Config\Database::connect();
    $fields = $db->getFieldData($table_name);
    foreach ($fields as $field) {
      if (array_key_exists($field->name, $data)) {
        if ((strlen($field->name) - strlen('_id') == strrpos($field->name, '_id'))
          && ((isset($data[$field->name]) && ($data[$field->name] == '')) || empty($data[$field->name]))
          && $field->primary_key
        )
          unset($data[$field->name]);
        else if ($data[$field->name] === '') {
          if ($field->type == 'tinyint') {
            $data[$field->name] = 0;
          } else {
            $data[$field->name] = null;
          }
        }
      }
    }
    $shared_data = $data;
    if ($table_name == 'medication_and_supply')
      unset($shared_data['rack_id']);
    if (!$is_m_n_table) {
      $data = array_merge($data, array(
        'createdby' => session()->get('simap_user_id'),
        'updatedby' => session()->get('simap_user_id')
      ));
    }
    return $this->model->_insert($table_name, $data, $shared_data);
  }

  public function _update($table_name, $data, $id, $is_increment = false, $is_m_n_table = false)
  {
    $db = \Config\Database::connect();
    $fields = $db->getFieldData($table_name);
    foreach ($fields as $field) {
      if (array_key_exists($field->name, $data)) {
        if ((strlen($field->name) - strlen('_id') == strrpos($field->name, '_id'))
          && ((isset($data[$field->name]) && ($data[$field->name] == '')) || empty($data[$field->name]))
          && $field->primary_key
        )
          unset($data[$field->name]);
        else if ($data[$field->name] === '') {
          if ($field->type == 'tinyint') {
            $data[$field->name] = 0;
          } else {
            $data[$field->name] = null;
          }
        }
      }
    }
    $shared_data = $data;
    if ($table_name == 'medication_and_supply')
      unset($shared_data['rack_id']);
    if (!$is_m_n_table) {
      $date = $is_increment ? ("'" . date('Y-m-d H:i:s') . "'") : date('Y-m-d H:i:s');
      $data = array_merge($data, array(
        'updateddate' => $date,
        'updatedby' => session()->get('simap_user_id')
      ));
    }
    return $this->model->_update($table_name, $data, $id, $is_increment, $shared_data);
  }

  public function _delete($table_name, $condition)
  {
    return $this->model->_delete($table_name, $condition);
  }

  public function _delete_join($data)
  {
    $this->model->_delete_join($data);
  }

  // get all in one
  public function get($query, $is_query_only = false)
  {
    return $this->model->get($query, $is_query_only);
  }

  public function get_custom($query)
  {
    return $this->model->get_custom($query);
  }

  public function count_all($query)
  {
    return $this->model->count_all($query);
  }

  // $table accepts both string and array, 
  // if string it will be received as table's name,
  // if array it will splitted to table's name and table's alias
  public function get_like_fields($table, $keyword, $foreign_table_keys = [], $hidden_fields = [], $special_fields = [])
  {
    $db = \Config\Database::connect();
    $table_name = '';
    $table_alias = '';

    if (is_array($table)) {
      $table_name = $table['name'];
      $table_alias = $table['alias'];
    } else {
      $table_name = $table;
      $table_alias = $table;
    }

    $fields = $db->getFieldData($table_name);
    $date_type = $this->get_date_type();
    $like_fields = array();

    foreach ($fields as $field) {
      if (in_array($field->name, $hidden_fields))
        continue;

      if (
        (!$field->primary_key && (strpos($field->name, 'is_') === false) && (strpos($field->name, 'created_') === false))
        || in_array($field->name, $special_fields)
      ) {
        if (in_array($field->type, $date_type))
          $like_fields[] = array("DATE_FORMAT({$table_alias}.{$field->name}, '%d %b %Y')", $keyword);
        else if ($field->name == 'month')
          $like_fields[] = array("MONTHNAME(STR_TO_DATE({$table_alias}.{$field->name}, '%m'))", $keyword);
        else if ($field->name == 'sex') {
          if (strpos('pria', strtolower($keyword)) !== false)
            $like_fields[] = array("{$table_alias}.{$field->name}", 'P');
          elseif (strpos('wanita', strtolower($keyword)) !== false)
            $like_fields[] = array("{$table_alias}.{$field->name}", 'W');
        } else if ($field->type == 'tinyint') {
          switch ($field->name) {
          }
        } else
          $like_fields[] = array("{$table_alias}.{$field->name}", $keyword);
      }
    }

    if (!empty($foreign_table_keys)) {
      foreach ($foreign_table_keys as $row) {
        if ($row[1] == 'bulan')
          $like_fields[] = array("MONTHNAME(STR_TO_DATE({$row[0]}.{$row[1]}, '%m'))", $keyword);
        else
          $like_fields[] = array("{$row[0]}.{$row[1]}", $keyword);
      }
    }

    return $like_fields;
  }

  public function call_function($function, $array_param)
  {
    return $this->model->call_function($function, $array_param);
  }
}
