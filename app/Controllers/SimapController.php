<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Database\Controllers\Database_controller;
use Modules\Pagination\Controllers\Pagination;
use Modules\Template\Controllers\Template;
use Modules\Utility\Controllers\Utility;
use Psr\Log\LoggerInterface;

class SimapController extends BaseController
{
  protected $custom_db;
  protected $pagination;
  protected $template;
  protected $utility;

  public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
  {
    parent::initController($request, $response, $logger);

    $this->custom_db = new Database_controller();
    $this->pagination = new Pagination();
    $this->template = new Template($request);
    $this->utility = new Utility();
  }
}
