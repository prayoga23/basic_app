<?php

//TODO: to be translated later
return array(
  // Default Rule
  'alpha'                 => 'The {field} field may only contain alphabetical characters.',
  'alpha_dash'            => 'The {field} field may only contain alphanumeric, underscore, and dash characters.',
  'alpha_numeric'         => 'The {field} field may only contain alphanumeric characters.',
  'alpha_numeric_punct'   => 'The {field} field may contain only alphanumeric characters, spaces, and  ~ ! # $ % & * - _ + = | : . characters.',
  'alpha_numeric_space'   => 'The {field} field may only contain alphanumeric and space characters.',
  'alpha_space'           => 'The {field} field may only contain alphabetical characters and spaces.',
  'decimal'               => '{field} harus bernilai angka bulat atau desimal.',
  'differs'               => 'The {field} field must differ from the {param} field.',
  'equals'                => '{field} harus berisi sesuai dengan Qty Tesedia = {param}.',
  'exact_length'          => 'The {field} field must be exactly {param} characters in length.',
  'greater_than'          => '{field} harus bernilai lebih besar dari {param}.',
  'greater_than_equal_to' => '{field} harus bernilai lebih besar dari atau sama dengan {param}.',
  'hex'                   => 'The {field} field may only contain hexidecimal characters.',
  'in_list'               => 'The {field} field must be one of: {param}.',
  'integer'               => 'The {field} field must contain an integer.',
  'is_natural'            => '{field} harus diisi angka.',
  'is_natural_no_zero'    => '{field} harus diisi angka dan bernilai lebih besar dari 0.',
  'is_not_unique'         => 'The {field} field must contain a previously existing value in the database.',
  'is_unique'             => 'The {field} field must contain a unique value.',
  'less_than'             => '{field} harus bernilai lebih kecil dari {param}.',
  'less_than_equal_to'    => '{field} harus bernilai lebih kecil dari atau sama dengan {param}.',
  'matches'               => '"{field}" harus sama dengan "{param}".',
  'max_length'            => 'Panjang karakter {field} tidak boleh melebihi {param} karakter.',
  'min_length'            => 'The {field} field must be at least {param} characters in length.',
  'not_equals'            => 'The {field} field cannot be: {param}.',
  'not_in_list'           => 'The {field} field must not be one of: {param}.',
  'numeric'               => '{field} harus angka.',
  'regex_match'           => 'The {field} field is not in the correct format.',
  'required'              => '{field} harus diisi.',
  'required_with'         => 'The {field} field is required when {param} is present.',
  'required_without'      => 'The {field} field is required when {param} is not present.',
  'string'                => 'The {field} field must be a valid string.',
  'timezone'              => 'The {field} field must be a valid timezone.',
  'valid_base64'          => 'The {field} field must be a valid base64 string.',
  'valid_email'           => 'The {field} field must contain a valid email address.',
  'valid_emails'          => 'The {field} field must contain all valid email addresses.',
  'valid_ip'              => 'The {field} field must contain a valid IP.',
  'valid_url'             => 'The {field} field must contain a valid URL.',
  'valid_date'            => 'The {field} field must contain a valid date.',

  // Default Files
  'uploaded'        => '{field} belum diupload.',
  'max_size'        => 'Ukuran {field} terlalu besar.',
  'is_image'        => '{field} bukan file gambar.',
  'mime_in'         => 'Tipe file {field} tidak valid.',
  'ext_in'          => '{field} does not have a valid file extension.',
  'max_dims'        => '{field} is either not an image, or it is too wide or tall.',

  // Custom rules
  'is_required'            => '{field} harus terisi setidaknya 1 barang.',
  'is_qty_allowed'         => '{field} yang tersedia tidak mencukupi.',
  'is_decimal_or_fraction' => '{field} harus terisi dengan angka bulat, angka desimal, atau pecahan',
);