<?php

namespace Modules\Database\Models;

use CodeIgniter\Model;

class Database_model extends Model
{

  public function _insert($table_name, $data, $shared_data)
  {
    $builder = $this->db->table($table_name);

    foreach ($data as $key => $row) {
      if ($row == '')
        unset($data[$key]);
    }

    $builder->insert($data);

    // NB insert into query log
    $id = $this->db->insertID();

    if ($this->db->affectedRows() > 0) {
      $this->log_queries($this->db);

      $temp_table_name = explode(' ', $table_name)[0];
      if (in_array($temp_table_name, $this->get_fetch_tables()))
        $this->insert_local_query(
          $builder->set($shared_data)->getCompiledInsert(),
          $temp_table_name,
          (!empty($id) ? $id : ''),
          'insert'
        );

      return $id;
    } else {
      log_message('error', 'Error on insert (' . $this->db->getLastQuery() . ') : ' . json_encode($this->db->error()));
      return -1;
    }
  }

  public function _update($table_name, $data, $id, $is_increment, $shared_data)
  {
    $status = false;
    $builder = $this->db->table($table_name);

    $builder->where($id);
    foreach ($data as &$row) {
      if ($row == '')
        unset($row);
    }
    if ($is_increment) {
      foreach ($data as $key => $value)
        $builder->set($key, $value, false);
      $status = $builder->update();
    } else
      // NB insert into query log
      $status = $builder->update($data);

      $affected_rows = $this->db->affectedRows();

      if ($affected_rows > 0) {
      $this->log_queries($this->db);

      $temp_table_name = explode(' ', $table_name)[0];
      if (in_array($temp_table_name, $this->get_fetch_tables())) {
        $this->insert_local_query(
          $builder->set($shared_data)->getCompiledUpdate(),
          $temp_table_name,
          $id['id'] ?? null,
          'update'
        );
      }
    }

    if (!$status)
      log_message('error', 'Error on update (' . $this->db->getLastQuery() . ') : ' . json_encode($this->db->error()));

    // return affected rows on success
    return $status ? $affected_rows : $status;
  }

  public function _delete($table_name, $condition)
  {
    $builder = $this->db->table($table_name);

    // NB insert into query log
    if ($builder->delete($condition)) {
      $this->log_queries($this->db);

      $temp_table_name = explode(' ', $table_name)[0];
      if (in_array($temp_table_name, $this->get_fetch_tables()))
        $this->insert_local_query(
          $builder->where($condition)->getCompiledDelete(),
          $temp_table_name,
          $condition['id'],
          'delete'
        );

      return true;
    } else {
      log_message('error', 'Error on delete (' . $this->db->getLastQuery() . ') : ' . json_encode($this->db->error()));
      return false;
    }
  }

  public function _delete_join($data)
  {
    // specify from which table data will be deleted, if there's more than one, separate them by comma
    $query = "DELETE " . $data['delete'];

    $query .= " FROM " . $data['table'];
    foreach ($data['join'] as $join) {
      $query .= " JOIN {$join[0]} ON {$join[1]}";
    }

    $where = [];
    foreach ($data['where'] as $field => $value) {
      $where[] = "{$field} = '{$value}'";
    }
    $query .= " WHERE " . implode(" AND ", $where);

    if ($this->db->query($query)) {
      $this->log_queries();
      return true;
    } else {
      log_message('error', 'Error on delete_join (' . $this->db->getLastQuery() . ') : ' . json_encode($this->db->error()));
      return false;
    }
  }

  // get all in one
  public function get($query, $is_query_only)
  {
    $builder = $this->db->table($query['table']);

    if (isset($query['where'])) {
      if (isset($query['is_or_where']))
        $builder->orWhere($query['where']);
      else
        $builder->where($query['where']);
    }

    if (isset($query['or_where'])) {
      if (!empty($query['or_where'])) {
        $inside_brackets = '';
        foreach ($query['or_where'] as $or_where) {
          if (!empty($or_where)) {
            $inside_brackets .= (empty($inside_brackets) ? '' : ' OR ') . '(';
            $new_query = '';
            foreach ($or_where as $key => $condition) {
              $new_query .= empty($new_query) ? $key . $condition
                : ' AND ' . $key . $condition;
            }
            $inside_brackets .= $new_query . ')';
          }
        }
        $builder->where('(' . $inside_brackets . ')', null, false);
      }
    }

    if (isset($query['join'])) {
      foreach ($query['join'] as $row) {
        $join = isset($row[2]) ? $row[2] : '';
        $builder->join($row[0], $row[1], $join);
      }
    }

    $like_counter = 0;
    if (isset($query['like']) && !empty($query['like'])) {
      $inside_bracket = '';
      $is_or_like = isset($query['is_or_like']);
      foreach ($query['like'] as $row) {
        if (isset($query['inside_brackets'])) {
          $like_query = $row[0] . ' LIKE "' .
            (isset($row[2]) ?
              ($row[2] == 'after' ? ($row[1] . '%') : ('%' . $row[1]))
              : ('%' . $row[1] . '%')) . '"';
          if ($inside_bracket != '') {
            if (!$is_or_like)
              $inside_bracket .= ' AND ';
            else
              $inside_bracket .= ' OR ';
          }
          $inside_bracket .= $like_query;
        } else {
          if ($like_counter == 0 || !$is_or_like)
            $builder->like($row[0], $row[1], (isset($row[2]) ? $row[2] : 'both'));
          else
            $builder->orLike($row[0], $row[1], (isset($row[2]) ? $row[2] : 'both'));

          $like_counter++;
        }
      }
      if (isset($query['inside_brackets'])) {
        $builder->where('(' . $inside_bracket . ')', null, false);
      }
    }

    if (isset($query['orderby']))
      $builder->orderBy($query['orderby']);

    if (isset($query['field']))
      $builder->select($query['field'], false);

    if (isset($query['groupby']))
      $builder->groupBy($query['groupby']);

    if (isset($query['limit'])) {
      $offset = 0;
      $limit = 1000;
      $limit = $query['limit'];
      if (isset($query['offset']))
        $offset = $query['offset'];
      $builder->limit($limit, $offset);
    }

    if ($is_query_only)
      return $builder->getCompiledSelect();

    $result = $builder->get();

    if (!$result || $result->getNumRows() == 0) {
      return false;
    }

    return $result;
  }

  public function get_custom($query)
  {
    $result = $this->db->query($query);
    if ($result && $result->getNumRows() == 0) {
      $result->freeResult();
      return false;
    }
    return $result;
  }

  public function count_all($query)
  {
    $builder = $this->db->table($query['table']);

    if (isset($query['where']))
      $builder->where($query['where']);

    if (isset($query['join'])) {
      foreach ($query['join'] as $row) {
        $join = isset($row[2]) ? $row[2] : '';
        $builder->join($row[0], $row[1], $join);
      }
    }

    $like_counter = 0;
    if (isset($query['like']) && !empty($query['like'])) {
      $inside_bracket = '';
      $is_or_like = isset($query['is_or_like']);
      foreach ($query['like'] as $row) {
        if (isset($query['inside_brackets'])) {
          $like_query = $row[0] . ' LIKE "' .
            (isset($row[2]) ?
              ($row[2] == 'after' ? ($row[1] . '%') : ('%' . $row[1]))
              : ('%' . $row[1] . '%')) . '"';
          if ($inside_bracket != '') {
            if (!$is_or_like)
              $inside_bracket .= ' AND ';
            else
              $inside_bracket .= ' OR ';
          }
          $inside_bracket .= $like_query;
        } else {
          if ($like_counter == 0 || !$is_or_like)
            $builder->like($row[0], $row[1], (isset($row[2]) ? $row[2] : 'both'));
          else
            $builder->orLike($row[0], $row[1], (isset($row[2]) ? $row[2] : 'both'));

          $like_counter++;
        }
      }
      if (isset($query['inside_brackets'])) {
        $builder->where('(' . $inside_bracket . ')', null, false);
      }
    }

    if (isset($query['field']))
      $builder->select($query['field'], false);

    if (isset($query['groupby']))
      $builder->groupBy($query['groupby']);

    return $builder->countAllResults();
  }

  private function get_fetch_tables()
  {
    return array(
      'base_unit', 'category', 'distributor', 'doctor', 'patient',
      'dosage_unit', 'emballage', 'instruction_frequency', 'instruction_route',
      'medication_and_supply', 'medication_package', 'medication_type',
      'purchase_unit', 'rack', 'margin', 'patient_has_membership',
      'sales_unit'
    );
  }

  private function insert_local_query($query, $table_name, $id, $process)
  {
    $builder = $this->db->table('local_query');
    $builder->insert(
      array(
        'query_string' => $query, 'table' => $table_name,
        'data_id' => $id, 'process' => $process
      )
    );
  }

  public function call_function($function, $array_param)
  {
    $result = $this->db->query('CALL ' . $function . '(' . implode(', ', $array_param) . ');');
    $this->log_queries();

    if (empty($result) || !is_object($result))
      log_message('error', "Error on procedure call (CALL $function(" . implode(', ', $array_param) . ");) : " . json_encode($this->db->error()));

    return $result;
  }

  private function log_queries()
  {
    date_default_timezone_set(_timezone);
    $filepath = WRITEPATH . 'logs/Tx-log-' . date('Y-m-d') . '.php'; // Creating Query Log file with today's date in application/logs folder
    $handle = fopen($filepath, "a+"); // Opening file with pointer at the end of the file

    $sql = $this->db->getLastQuery() . " \n Execution Time:" . date('Y-m-d H:i:s'); // Generating SQL file alongwith execution time
    fwrite($handle, $sql . "\n\n"); // Writing it in the log file

    fclose($handle);      // Close the file
  }
}
