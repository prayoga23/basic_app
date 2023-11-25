<?php

namespace Modules\Shift\Controllers;

use App\Controllers\SimapController;

class Shift extends SimapController
{
  private function get_table_name()
  {
    return 'shift';
  }

  private function get_module()
  {
    return 'shift';
  }

  public function get_title()
  {
    return 'Jam Kerja';
  }

  public function index()
  {
    // Change value of string data below according to implemented module
    $data['module'] = $this->get_module();
    $data['view_file'] = 'shift_view';
    $data['title'] = $this->get_title();
    $data['is_creating'] = true;
    $data['is_list_view'] = true;

    $limit = (config('Pager'))->perPage;
    $page = $this->request->getGet('page', FILTER_SANITIZE_NUMBER_INT) ?: 0;
    $last_no = ($page > 0 ? $page - 1 : $page) * $limit;

    $query = array(
      'table' => $this->get_table_name(),
      'groupby' => 'id',
      'orderby' => 'start_time, end_time',
      'limit' => $limit,
      'offset' => $last_no,
    );

    // Pagination
    $total_rows = $this->custom_db->count_all($query);
    $data['pagination'] = $this->pagination->get_pagination($page, $limit, $total_rows, $last_no);

    $data['data'] = $this->custom_db->get($query);
    $this->template->run($data);
  }

  public function search()
  {
    $keyword = strtoupper(esc($this->request->getGet('keyword')));

    $data['search'] = rawurldecode($keyword);
    $data['module'] = $this->get_module();
    $data['view_file'] = 'shift_view';
    $data['title'] = $this->get_title();
    $data['is_creating'] = true;
    $data['is_list_view'] = true;

    $likes = $this->custom_db->get_like_fields($this->get_table_name(), rawurldecode($keyword), '', array('id', 'createddate', 'createdby', 'updateddate', 'updatedby'));

    $limit = (config('Pager'))->perPage;
    $page = $this->request->getGet('page', FILTER_SANITIZE_NUMBER_INT) ?: 0;
    $last_no = ($page > 0 ? $page - 1 : $page) * $limit;

    $query = array(
      'table' => $this->get_table_name(),
      'like'  => $likes,
      'is_or_like'      => true,
      'inside_brackets' => true,
      'groupby' => 'id',
      'orderby' => 'start_time, end_time',
      'limit'   => $limit,
      'offset'  => $last_no
    );

    // Pagination
    $total_rows = $this->custom_db->count_all($query);
    $data['pagination'] = $this->pagination->get_pagination($page, $limit, $total_rows, $last_no);

    $data['data'] = $this->custom_db->get($query);
    $this->template->run($data);
  }

  private function get_post_data()
  {
    $data = array();
    $fields = array('id', 'start_time', 'end_time');
    foreach ($fields as $field)
      $data[$field] = strtoupper(esc($this->request->getPost($field)) ?? '');
    return $data;
  }

  public function add($data = [])
  {
    $data = array_merge($data, $this->get_post_data());
    $data['module'] = $this->get_module();
    $data['list_time'] = $this->get_time();
    $data['view_file'] = 'update_shift_view';
    $data['title'] = 'Tambah ' . $this->get_title();
    $data['cancel_url'] = base_url($this->get_module());
    $this->template->run($data);
  }

  public function edit($id, $data = [])
  {
    if (is_numeric($id)) {
      $db_data = $this->custom_db->get(array(
        'table' => $this->get_table_name(),
        'where' => array('id' => $id)
      ));

      if (!empty($db_data)) {
        $data = array_merge($data, $db_data->getRowArray());
        $data['title'] = 'Ubah ' . $this->get_title();
        $data['module'] = $this->get_module();
        $data['list_time'] = $this->get_time();
        $data['view_file'] = 'update_shift_view';
        $data['cancel_url'] = base_url($this->get_module());
        $this->template->run($data);
      } else {
        session()->setFlashdata('error_message', $this->get_title() . ' tidak ditemukan');
        return redirect()->to((isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : (base_url($this->get_module()))));
      }
    } else {
      return redirect()->to((isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : (base_url($this->get_module()))));
    }
  }

  public function submit()
  {
    $data = $this->get_post_data();
    $is_edit = is_numeric($data['id']);
    $id = $is_edit ? $data['id'] : 0;

    $rules = array(
      'start_time' => array(
        'label' => 'Waktu Awal Kerja',
        'rules' => 'required',
      ),
      'end_time' => array(
        'label' => 'Waktu Akhir Kerja',
        'rules' => 'required',
      ),
    );

    if ($this->validate($rules) == false) {
      if ($is_edit)
        $this->edit($id, array('validation' => $this->validator));
      else
        $this->add(array('validation' => $this->validator));
    } else {
      if ($is_edit) {
        $this->custom_db->_update($this->get_table_name(), $data, array('id' => $id));
        session()->setFlashdata('success_message', $this->get_title() . ' berhasil diubah');
      } else {
        $this->custom_db->_insert($this->get_table_name(), $data);
        session()->setFlashdata('success_message', $this->get_title() . ' berhasil ditambahkan');
      }
      return redirect()->to(base_url($this->get_module()));
    }
  }

  public function delete($id)
  {
    if (is_numeric($id)) {
      $condition = array('id' => $id);
      $data = $this->custom_db->get(array(
        'table' => $this->get_table_name(),
        'where' => $condition
      ));

      if (!empty($data)) {
        $is_deleted = $this->custom_db->_delete($this->get_table_name(), $condition);
        if ($is_deleted) {
          session()->setFlashdata('success_message', $this->get_title() . ' berhasil dihapus');
        } else
          session()->setFlashdata('error_message', 'Data tidak dapat dihapus, data sudah digunakan');
      } else
        session()->setFlashdata('error_message', 'Proses hapus gagal');
    }

    return redirect()->to(base_url($this->get_module()));
  }

  private function get_time()
  {
    $array_time[''] = '-- Pilih --';

    for ($i = 1; $i < 25; $i++) {
      $array_time[$i] = ($i < 10 ? '0' : '') . $i . '.00';
    }

    return $array_time;
  }
}
