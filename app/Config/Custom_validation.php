<?php

namespace Config;

use Modules\Database\Controllers\Database_controller;

class Custom_validation
{
  protected $custom_db;

  public function __construct()
  {
    $this->custom_db = new Database_controller();
  }

  // $params consist of 4 parts: table_name (required), id (required), fields (required), 
  //                             additional values to be checked (optional)
  // for multiple fields and values, separate them with ;
  public function is_exist(?string $str, string $params, array $data = array(), ?string &$error = null): bool
  {
    list($table_name, $id, $fields, $values) = array_pad(explode(',', $params), 4, '');

    $where = [];
    $tables_with_no_is_active = array('target');
    $tables_with_custom_error_message = array('target', 'distributor', 'purchase_unit', 'sales_unit', 'bank');

    if (in_array($table_name, $tables_with_no_is_active) === false)
      $where = array('is_active' => true);

    $fields = explode(';', $fields);
    if ($values != '')
      $values = explode(';', $values);

    if (count($fields) == 1)
      $where = array_merge($where, array($fields[0] => $str));
    else {
      foreach ($fields as $key => $field) {
        $where[$field] = $values[$key];
      }
    }

    if ($id != 0)
      $where = array_merge($where, array('id !=' => $id));

    $db_data = $this->custom_db->get(array(
      'table' => $table_name,
      'where' => $where
    ));

    if (!empty($db_data)) {
      if (in_array($table_name, $tables_with_custom_error_message) === false)
        $error = '"' . strtoupper($str) . '" sudah terdaftar';

      return false;
    }

    return true;
  }
}
