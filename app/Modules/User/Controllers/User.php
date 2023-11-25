<?php

namespace Modules\User\Controllers;

use App\Controllers\SimapController;
use Modules\Additional_module\Controllers\Additional_module;
use Modules\Credential\Controllers\Credential;

class User extends SimapController
{
  protected $credential;

  public function __construct()
  {
    $this->credential = new Credential();
  }

  private function get_table_name()
  {
    return 'user';
  }

  private function get_module()
  {
    return 'user';
  }

  public function get_title()
  {
    return 'Karyawan';
  }

  public function index()
  {
    // Change value of string data below according to implemented module
    $data['module'] = $this->get_module();
    $data['view_file'] = 'user_view';
    $data['title'] = $this->get_title();
    $data['is_creating'] = true;
    $data['is_list_view'] = true;
    $data['status_access'] = $this->credential->has_access($this->get_module(), $this->get_title());

    $limit = (config('Pager'))->perPage;
    $page = $this->request->getGet('page') ?? 0;
    $last_no = ($page > 0 ? $page - 1 : $page) * $limit;

    $query = array(
      'table' => $this->get_table_name(),
      'groupby' => 'id',
      'orderby' => 'name',
      'limit' => $limit,
      'offset' => $last_no,
      'where' => array('is_active' => true)
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
    $data['view_file'] = 'user_view';
    $data['title'] = $this->get_title();
    $data['is_creating'] = true;
    $data['is_list_view'] = true;
    $data['status_access'] = $this->credential->has_access($this->get_module(), $this->get_title());

    $likes = $this->custom_db->get_like_fields($this->get_table_name(), rawurldecode($keyword), '', array('id', 'createddate', 'createdby', 'updateddate', 'updatedby'));

    $limit = (config('Pager'))->perPage;
    $page = $this->request->getGet('page') ?? 0;
    $last_no = ($page > 0 ? $page - 1 : $page) * $limit;

    $query = array(
      'table' => $this->get_table_name(),
      'is_or_like' => true,
      'like' => $likes,
      'groupby' => 'id',
      'orderby' => 'name',
      'limit' => $limit,
      'offset' => $last_no,
      'where' => array('is_active' => true)
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
    $fields = array(
      'id', 'username', 'name', 'sex', 'birthday', 'address',
      'id_no', 'city', 'phone', 'menu_access', 'is_prec_psyc_report',
      'pharmacist_registration', 'pharmacist_privilege',
      'is_due_date_notification', 'is_credit_sales_notification',
      'is_price_verification_notification',
      'is_sales_approval', 'has_access_dashboard'
    );
    foreach ($fields as $field) {
      if ($field == 'menu_access')
        $data[$field] = esc($this->request->getPost($field));
      else
        $data[$field] = strtoupper(esc($this->request->getPost($field)) ?? '');
    }

    $temp_access = $data['menu_access'];
    $menu_access = '';
    if (!empty($temp_access)) {
      foreach ($temp_access as $access) {
        $menu_access .= $access . '.' . esc($this->request->getPost('drop_' . $access)) . ',';
      }
      $menu_access = substr($menu_access, 0, strlen($menu_access) - 1);
    }
    $data['menu_access'] = $menu_access;

    return $data;
  }

  public function add($data = [])
  {
    $data = array_merge($data, $this->get_post_data());
    $data['module'] = $this->get_module();
    $data['pages'] = $this->get_pages();
    $data['view_file'] = 'update_user_view';
    $data['title'] = 'Tambah ' . $this->get_title();
    $data['cancel_url'] = base_url($this->get_module());
    $this->template->run($data);
  }

  public function edit($id, $data = [])
  {
    if (is_numeric($id)) {
      $data = array_merge($data, $this->get_post_data());

      if (empty($data['id'])) {
        $data = $this->custom_db->get(array(
          'table' => $this->get_table_name(),
          'where' => array('id' => $id, 'is_active' => true)
        ));
        if (!empty($data))
          $data = $data->getRowArray();
      }

      if (!empty($data['id'])) {
        $data['title'] = 'Ubah ' . $this->get_title();
        $data['module'] = $this->get_module();
        $data['pages'] = $this->get_pages();
        $data['view_file'] = 'update_user_view';
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
      'username' => array(
        'label' => 'Username',
        'rules' => "required|is_exist[{$this->get_table_name()},$id,username]",
      ),
      'name' => array('label' => 'Nama', 'rules' => 'required'),
      'birthday' => array('label' => 'Tanggal Lahir', 'rules' => 'required'),
      'id_no' => array('label' => 'No. KTP', 'rules' => 'required'),
      'address' => array('label' => 'Alamat', 'rules' => 'required'),
      'city' => array('label' => 'Kota', 'rules' => 'required'),
      'phone' => array('label' => 'Telp', 'rules' => 'required'),
      'menu_access' => array('label' => 'Akses Menu', 'rules' => 'required'),
    );

    if ($data['is_prec_psyc_report']) {
      $rules['pharmacist_registration'] = array('label' => 'No. SIPA', 'rules' => 'required');
      $rules['pharmacist_privilege'] = array('label' => 'Jabatan Apoteker', 'rules' => 'required');
    }

    if ($this->validate($rules) == false) {
      if ($is_edit)
        $this->edit($id, array('validation' => $this->validator));
      else
        $this->add(array('validation' => $this->validator));
    } else {
      $verified_pages = str_replace('.2', '', str_replace('.1', '', str_replace('.0', '', $data['menu_access'])));

      $array_menu = $this->create_array_menu($verified_pages);
      $data['html_access'] = $this->create_html_access($array_menu);

      if ($is_edit) {
        $this->custom_db->_update($this->get_table_name(), $data, array('id' => $id));
        session()->setFlashdata('success_message', $this->get_title() . ' berhasil diubah');
      } else {
        $data['password'] = md5(strtoupper('pass1234'));
        $this->custom_db->_insert($this->get_table_name(), $data);
        session()->setFlashdata('success_message', $this->get_title() . ' berhasil ditambahkan');
      }
      //TODO: Modules::run('sync_central/fetch');
      return redirect()->to(base_url($this->get_module()));
    }
  }

  public function delete($id)
  {
    if (is_numeric($id)) {
      $condition = array('id' => $id, 'is_super_admin' => false);
      $data = $this->custom_db->get(array(
        'table' => $this->get_table_name(),
        'where' => $condition
      ));

      if (!empty($data)) {
        $is_deleted = $this->custom_db->_update($this->get_table_name(), array('is_active' => false), $condition);
        if ($is_deleted) {
          //TODO: Modules::run('sync_central/fetch');
          session()->setFlashdata('success_message', $this->get_title() . ' berhasil dihapus');
        } else
          session()->setFlashdata('error_message', 'Data tidak dapat dihapus, data sudah digunakan');
      } else
        session()->setFlashdata('error_message', 'Proses hapus gagal');
    }

    return redirect()->to(base_url($this->get_module()));
  }

  public function reset_password($username)
  {
    $user = $this->custom_db->get(array(
      'table' => $this->get_table_name(),
      'where' => array('username' => $username)
    ));

    if (!empty($user)) {
      $user = $user->getRowArray();
      if ($user['is_new_password']) {
        $this->custom_db->_update(
          $this->get_table_name(),
          array('password' => md5(strtoupper('pass1234')), 'is_new_password' => false),
          array('id' => $user['id']),
          false,
          true
        );
        $this->custom_db->_insert(
          'password_reset_log',
          array('user_id' => $user['id'], 'createdby' => session()->get('simap_user_id')),
          true
        );
        session()->setFlashdata('success_message', "Reset sandi karyawan \"{$user['name']}\" berhasil");
      } else {
        session()->setFlashdata('error_message', "Sandi karyawan \"{$user['name']}\" belum diperbarui");
      }
    } else {
      session()->setFlashdata('error_message', 'Data karyawan tidak ditemukan');
    }

    return redirect()->to(base_url($this->get_module()));
  }

  private function get_pages()
  {
    $additional_module = new Additional_module();

    $sales_array = array(
      array(
        'title' => 'Penjualan',
        'value' => 'sales',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Nota Penjualan',
        'value' => 'sales_receipt'
      ),
      array(
        'title' => 'Nota Resep',
        'value' => 'prescription_receipt',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Retur Penjualan',
        'value' => 'sales_return',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Promosi',
        'value' => 'promotion'
      ),
      array(
        'title' => 'Pengajuan Opname Kas',
        'value' => 'cash_opname_submission',
      ),
      array(
        'title' => 'Data Opname Kas',
        'value' => 'cash_opname',
        'is_functional_page' => true,
      ),
    );

    $customer_array =  array(
      array(
        'title' => 'Pasien',
        'value' => 'patient'
      ),
      array(
        'title' => 'Member',
        'value' => 'member'
      )
    );

    $clinical_array =  array(
      array(
        'title' => 'Rekam Medis',
        'value' => 'medical_record',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Dokter',
        'value' => 'doctor'
      ),
      array(
        'title' => 'KIE',
        'value' => 'counseling'
      )
    );

    $stock_array = array(
      array(
        'title' => 'Kartu Stok',
        'value' => 'stock_card',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Penyesuaian Stok',
        'value' => 'stock_adjustment'
      ),
      array(
        'title' => 'Stock Opname',
        'value' => 'stock_opname'
      )
    );
    $stock_array = $additional_module->extra_menu_stock($stock_array);

    $procurement_array = array(
      array(
        'title' => 'Surat Pesanan',
        'value' => 'purchase_order'
      ),
      array(
        'title' => 'Penerimaan Pesanan',
        'value' => 'order_reception'
      ),
      array(
        'title' => 'Nota Pembelian',
        'value' => 'payment_receipt'
      ),
      array(
        'title' => 'Retur Pembelian',
        'value' => 'purchase_return',
        'is_functional_page' => true
      )
    );
    $procurement_array = $additional_module->extra_menu_procurement($procurement_array);

    $payment_array = array(
      array(
        'title' => 'Hutang',
        'value' => 'payable_payment',
      ),
      array(
        'title' => 'Piutang',
        'value' => 'receivable_payment'
      )
    );

    $report_array = array(
      array(
        'title' => 'Penjualan Per Jam Kerja',
        'value' => 'report/daily_sales',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Penjualan',
        'value' => 'report/sales',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Pembelian',
        'value' => 'report/purchase',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Retur Penjualan',
        'value' => 'report/sales_return',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Retur Pembelian',
        'value' => 'report/purchase_return',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Umur Hutang',
        'value' => 'report/payable_aging',
        'is_functional_page' => true,
      ),
      array(
        'title' => 'Pembayaran Hutang',
        'value' => 'report/payable_payment',
        'is_functional_page' => true,
      ),
      array(
        'title' => 'Umur Piutang',
        'value' => 'report/receivable_aging',
        'is_functional_page' => true,
      ),
      array(
        'title' => 'Pembayaran Piutang',
        'value' => 'report/receivable_payment',
        'is_functional_page' => true,
      ),
      array(
        'title' => 'Produk',
        'value' => 'report/product',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Reset Sandi',
        'value' => 'report/password_reset',
        'is_functional_page' => true
      )
    );
    $report_array = $additional_module->extra_menu_report($report_array);

    $master_array = array(
      array(
        'title' => 'Area Pelanggan',
        'value' => 'customer_area',
      ),
      array(
        'title' => 'Bank',
        'value' => 'bank',
      ),
      array(
        'title' => 'Distributor',
        'value' => 'distributor'
      ),
      array(
        'title' => 'Golongan Produk',
        'value' => 'medication_type'
      ),
      array(
        'title' => 'Jam Kerja',
        'value' => 'shift'
      ),
      array(
        'title' => 'Jenis Pelanggan',
        'value' => 'customer_group',
      ),
      array(
        'title' => 'Karyawan & Akses',
        'value' => 'user'
      ),
      array(
        'title' => 'Kategori Produk',
        'value' => 'category'
      ),
      array(
        'title' => 'Merek Produk',
        'value' => 'brand',
      ),
      array(
        'title' => 'Produk',
        'value' => 'product'
      ),
      array(
        'title' => 'Paket Medikasi',
        'value' => 'medication_package'
      ),
      array(
        'title' => 'Rak Produk',
        'value' => 'rack'
      ),
      array(
        'title' => 'Resep - Embalase',
        'value' => 'emballage'
      ),
      array(
        'title' => 'Resep - Frekuensi Pemberian',
        'value' => 'instruction_frequency'
      ),
      array(
        'title' => 'Resep - Rute Pemberian',
        'value' => 'instruction_route'
      ),
      array(
        'title' => 'Satuan Dasar',
        'value' => 'base_unit'
      ),
      array(
        'title' => 'Satuan Beli',
        'value' => 'purchase_unit'
      ),
      array(
        'title' => 'Satuan Jual',
        'value' => 'sales_unit'
      ),
      array(
        'title' => 'Satuan Dosis',
        'value' => 'dosage_unit'
      ),
      array(
        'title' => 'Target Bulanan',
        'value' => 'target'
      ),
      array(
        'title' => 'Backup & Restore',
        'value' => 'backup_restore',
        'is_functional_page' => true
      )
    );
    $master_array = $additional_module->extra_menu_master($master_array);

    $accounting_array = array(
      array(
        'title' => 'Jurnal Voucher',
        'value' => 'acc_journal_voucher',
      ),
      array(
        'title' => 'Penerimaan Kas',
        'value' => 'acc_cash_reception',
      ),
      array(
        'title' => 'Perpindahan Antar Kas',
        'value' => 'acc_cash_transfer',
      ),
      array(
        'title' => 'Pengeluaran Kas',
        'value' => 'acc_cash_disbursement',
      ),
      array(
        'title' => 'Penutupan Periode',
        'value' => 'acc_closed_period',
      ),
      array(
        'title' => 'Kelompok Akun',
        'value' => 'acc_account_group',
      ),
      array(
        'title' => 'Daftar Akun',
        'value' => 'acc_account',
      ),
      array(
        'title' => 'Pemetaan Akun',
        'value' => 'acc_account_map',
      ),
      array(
        'title' => 'Laporan Jurnal',
        'value' => 'acc_report/ledger',
        'is_functional_page' => true,
      ),
      array(
        'title' => 'Laporan Buku Besar',
        'value' => 'acc_report/general_ledger',
        'is_functional_page' => true,
      ),
      array(
        'title' => 'Laporan Neraca Saldo',
        'value' => 'acc_report/trial_balance',
        'is_functional_page' => true,
      ),
      array(
        'title' => 'Laporan Laba Rugi',
        'value' => 'acc_report/profit_loss',
        'is_functional_page' => true,
      ),
      array(
        'title' => 'Laporan Neraca',
        'value' => 'acc_report/balance_sheet',
        'is_functional_page' => true,
      ),
    );

    $setting_array = array(
      array(
        'title' => 'Teknis',
        'value' => 'setting',
        'is_functional_page' => true
      )
    );
    $setting_array = $additional_module->extra_menu_setting($setting_array);

    $menu_list = array(
      '<b><span class="fa fa-shopping-bag"></span> <span>PENJUALAN</span></b>' => $sales_array,
      '<b><span class="fa fa-user"></span> <span>PELANGGAN</span></b>' => $customer_array,
      '<b><span class="fa fa-clinic-medical"></span> <span>RAWAT JALAN</span></b>' => $clinical_array,
      '<b><span class="fa fa-archive"></span> <span>STOK</span></b>' => $stock_array
    );

    $network_array = $additional_module->extra_menu_network(array());
    if (!empty($network_array))
      $menu_list['<b><span class="fa fa-globe"></span> <span>JARINGAN</span></b>'] = $network_array;

    $menu_list = array_merge($menu_list, array(
      '<b><span class="fa fa-truck"></span> <span>PENGADAAN BARANG</span></b>' => $procurement_array,
      '<b><span class="fa fa-calculator"></span> <span>PEMBAYARAN</span></b>' => $payment_array,
      '<b><span class="fa fa-clipboard"></span> <span>LAPORAN</span></b>' => $report_array,
      '<b><span class="fa fa-database"></span> <span>MASTER</span></b>' => $master_array,
      '<b><span class="fa fa-book"></span> <span>AKUNTANSI</span></b>' => $accounting_array,
      '<b><span class="fa fa-tasks"></span> <span>VERIFIKASI</span></b>' => 'price_adjustment_verification',
      '<b><span class="fa fa-cogs"></span> <span>PENGATURAN</span></b>' => $setting_array,
    ));

    return $menu_list;
  }

  private function create_array_menu($string_menu)
  {
    $available_menu = explode(',', $string_menu);
    $array_menu = array();
    $pages = $this->get_pages();
    foreach ($pages as $key => $page) {
      $counter = 0;
      foreach ($available_menu as $row) {
        if (is_int($index = (is_array($page) ? array_search(
          $row,
          array_map(function ($val) {
            return $val['value'];
          }, $page)
        ) : strpos($page, $row)))) {
          if (!isset($array_menu[$key])) {
            $array_menu[$key] = array();
          }
          if (is_array($page)) {
            $array_menu[$key][$counter]['url'] = $row;
            $array_menu[$key][$counter]['title'] = $page[$index]['title'];
          } else {
            $array_menu[$key][$counter] = $row;
          }
          $counter++;
        }
      }
    }
    return $array_menu;
  }

  private function create_html_access($menu_access)
  {
    $setting = $this->custom_db->get(array(
      'table' => 'setting'
    ))->getRowArray();

    $base_url = base_url();

    if (!empty($setting['base_url']) && (base_url() != $setting['base_url']))
      $base_url = $setting['base_url'];

    $logo_url = $base_url . '/files/img/properties/simap_logo.png';

    $menu = "
      <div id='simap_logo-container'>
        <a href='$base_url'><img src='$logo_url' id='simap_logo'/></a>
      </div>
      <div id='simap_menu-container'>
      <ul id='simap_menu'>
    ";

    foreach ($menu_access as $key => $row) {
      if (is_array($row[0])) {
        $menu .= '<li>' . $key;
        $menu .= '<ul>';
        foreach ($row as $data) {
          $menu .= "<li><a href='{$base_url}/{$data['url']}'>{$data['title']}</a></li>";
        }
        $menu .= '</ul>';
      } else {
        $menu .= "<li><a href='{$base_url}/{$row[0]}'>$key</a>";
      }
      $menu .= '</li>';
    }
    $menu .= '</ul></div>';

    return $menu;
  }
}
