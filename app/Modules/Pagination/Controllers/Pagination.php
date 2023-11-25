<?php

namespace Modules\Pagination\Controllers;

use App\Controllers\BaseController;

class Pagination extends BaseController
{
  protected $pagination;

  public function __construct()
  {
    $this->pagination = \Config\Services::pager();
  }

  public function get_pagination($page, $limit, $total_rows, $last_no)
  {
    // If page = 0, change it to 1 because pagination starts from 1
    $page = $page == 0 ? 1 : $page;

    return array(
      'links' => $this->pagination->makeLinks($page, $limit, $total_rows, '_pagination'),
      'page' => $page,
      'last_no' => $last_no,
      'total_rows' => $total_rows,
    );
  }
}
