<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);


define('_version', '2.0');
define('_subversion', '2.0.0');
define('_timezone', 'Asia/Jakarta');

define('_base_unit_help_info', 'Digunakan sebagai satuan acuan awal ' .
  'penjualan produk dan keterangan pada surat pesanan tertentu<br />
          <b>Contoh:</b> TABLET, PC, PACK, STRIP, dst...');
define('_purchase_unit_help_info', 'Digunakan sebagai satuan acuan pembelian produk<br/>' .
  '<b>Contoh:</b> BOX ISI 100, BOX ISI 10<br/>' .
  'DUS ISI 12, dst...');
define('_medication_package_help_info', '<b>Contoh:</b> TES ASAM URAT, TES GULA, TES KOLESTEROL, dst...');
define('_medication_type_help_info', '<b>Contoh:</b> NARKOTIKA, PSIKOTROPIKA<br/>' .
  'PREKURSOR, OOT, dst...');
define('_category_help_info', '<b>Contoh:</b> ANTIBIOTIK, ANALGESIK, GOUT ATHRITIS, dst...');
define('_sales_unit_help_info', 'Digunakan sebagai satuan acuan penjualan barang<br/>' .
  '<b>Contoh:</b> STRIP ISI 4, STRIP ISI 10, dst...');
define('_dosage_unit_help_info', '<b>Contoh:</b> GR (GRAM), LT (LITER),<br/>' .
  'MG (MILIGRAM), ML (MILILITER), dst...');
define('_frequency_help_info', '<b>Contoh:</b> 1 DD, 2 DD, 3 DD, 4 DD, dst...');
define('_route_help_info', '<b>Contoh:</b> ORAL, TRANSDERMAL, SUBLINGUAL, dst...');
define('_emballage_help_info', '<b>Contoh:</b><br/>' .
  'NON RACIK RP. 1.000,-<br/>' .
  'RACIK PUYER RP. 1.500,-<br/>' .
  'RACIK KAPSUL RP. 2.000,-<br/>' .
  'dst...');
define('_purchase_order_type', array(
  0 => 'STANDAR',
  1 => 'OBAT OBAT TERTENTU',
  2 => 'PREKURSOR',
  3 => 'NARKOTIKA & PSIKOTROPIKA'
));
define('_brand_help_info', '<b>Contoh:</b> ABBOT, KALBE, SANBE, dst...');