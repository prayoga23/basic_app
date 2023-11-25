<?php

namespace Modules\Additional_module\Controllers;

use App\Controllers\BaseController;
use Modules\Database\Controllers\Database_controller;

class Additional_module extends BaseController
{
  protected $custom_db;

  public function __construct()
  {
    $this->custom_db = new Database_controller();
  }

  public function extra_menu_procurement($array_menu)
  {
    if (method_exists($this, 'get_stock_planning')) {
      $array_menu[] = $this->get_stock_planning();
    }
    if (method_exists($this, 'get_consignment')) {
      array_splice($array_menu, 5, 0, $this->get_consignment());
    }
    return $array_menu;
  }

  public function extra_menu_stock($array_menu)
  {
    if (method_exists($this, 'get_stock_position')) {
      $array_menu[] = $this->get_stock_position();
    }
    return $array_menu;
  }

  public function extra_menu_report($array_menu)
  {
    if (method_exists($this, 'get_stock_movement')) {
      array_splice($array_menu, 5, 0, $this->get_stock_movement());
    }
    if (method_exists($this, 'get_price_movement')) {
      array_splice($array_menu, 5, 0, $this->get_price_movement());
    }
    if (method_exists($this, 'get_price_adjustment')) {
      array_splice($array_menu, 5, 0, $this->get_price_adjustment());
    }
    if (method_exists($this, 'get_commission')) {
      array_splice($array_menu, 6, 0, $this->get_commission());
    }
    return $array_menu;
  }

  public function extra_menu_master($array_menu)
  {
    if (method_exists($this, 'get_partner')) {
      array_splice($array_menu, 5, 0, $this->get_partner());
    }
    return $array_menu;
  }

  public function extra_menu_network($array_menu)
  {
    if (method_exists($this, 'get_network_menu')) {
      array_splice($array_menu, 0, 0, $this->get_network_menu());
    }
    return $array_menu;
  }

  public function extra_menu_setting($array_menu)
  {
    if (method_exists($this, 'get_additional_setting')) {
      array_splice($array_menu, 1, 0, $this->get_additional_setting());
    }
    return $array_menu;
  }

  private function get_stock_planning()
  {
    // last index
    return array(
      'title' => 'Perencanaan Stok',
      'value' => 'stock_planning',
      'is_functional_page' => true
    );
  }

  private function get_consignment()
  {
    // index 5
    return array(
      array(
        'title' => 'Penerimaan Konsinyasi',
        'value' => 'consignment_receipt'
      ),
      array(
        'title' => 'Pembayaran Konsinyasi',
        'value' => 'consignment_payment',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Nota Pembayaran Konsinyasi',
        'value' => 'consignment_payment_history',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Retur Konsinyasi',
        'value' => 'consignment_return',
        'is_functional_page' => true
      )
    );
  }

  private function get_stock_position()
  {
    // last index
    return array(
      'title' => 'Posisi Stok',
      'value' => 'stock_position',
      'is_functional_page' => true
    );
  }

  private function get_stock_movement()
  {
    // index 4
    return array(array(
      'title' => 'Pergerakan Stok',
      'value' => 'additional_report/stock_movement',
      'is_functional_page' => true
    ));
  }

  private function get_price_movement()
  {
    // index 4
    return array(array(
      'title' => 'Pergerakan Harga',
      'value' => 'additional_report/price_movement',
      'is_functional_page' => true
    ));
  }

  private function get_price_adjustment()
  {
    // index 4
    return array(array(
      'title' => 'Pembaharuan Harga',
      'value' => 'additional_report/price_adjustment',
      'is_functional_page' => true
    ));
  }

  private function get_commission()
  {
    // last index
    return array(array(
      'title' => 'Komisi',
      'value' => 'additional_report/commission',
      'is_functional_page' => true
    ));
  }

  private function get_partner()
  {
    // index 
    return array(array(
      'title' => 'Rekanan',
      'value' => 'partner'
    ));
  }

  private function get_network_menu()
  {
    return array(
      array(
        'title' => 'Stok Cabang',
        'value' => 'network_stock',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Penerimaan Retur Mutasi',
        'value' => 'network_return_examination',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Mutasi Keluar',
        'value' => 'network_transfer'
      ),
      array(
        'title' => 'Retur Mutasi Keluar',
        'value' => 'network_return_compliance',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Penerimaan Mutasi',
        'value' => 'network_transfer_examination',
        'is_functional_page' => true
      ),
      array(
        'title' => 'Mutasi Masuk',
        'value' => 'network_transfer_compliance'
      ),
      array(
        'title' => 'Retur Mutasi Masuk',
        'value' => 'network_compliance_return'
      ),
      array(
        'title' => 'Data Cabang',
        'value' => 'network_branch'
      )
    );
  }

  private function get_additional_setting()
  {
    return array(array(
      'title' => 'Umum',
      'value' => 'additional_setting',
      'is_functional_page' => true
    ));
  }
}
