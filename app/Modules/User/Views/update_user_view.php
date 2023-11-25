<script type="text/javascript" src="<?= base_url('files/js/simap_jquery-ui.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('files/js/simap_jquery-ui-tabs.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('files/js/bootstrap-datepicker.js'); ?>"></script>

<?php
function get_access($module, $array_access)
{
  if (!empty($array_access)) {
    $array_module = explode(',', $array_access);
    $array_module_access = array();
    foreach ($array_module as $data) {
      $new_array = explode('.', $data);
      $array_module_access[$new_array[0]] = isset($new_array[1]) ? $new_array[1] : '';
    }

    $value = 0;
    if (array_key_exists($module, $array_module_access))
      $value = $array_module_access[$module];

    return $value;
  }
}

$confirm_msg = 'Apakah anda yakin untuk ' . (is_numeric($id) ? 'mengubah' : 'menambah') . ' data Karyawan ?';
echo form_open("$module/submit", array('onsubmit' => "return confirm('$confirm_msg')"));
if (is_numeric($id)) {
  echo form_hidden('id', $id);
}
?>

<div id="simap_tabs">
  <ul>
    <li><a href="#simap_tab1">Data Karyawan</a></li>
    <li><a href="#simap_tab2" class="<?= (isset($validation) && $validation->hasError('menu_access') ? 'simap_tab-error' : ''); ?>">Akses Menu</a></li>
  </ul>

  <div id="simap_tab1" class="simap_tab">
    <?php
    echo '<div class="simap_two-column">';
    echo form_label('Username');
    echo form_input('username', $username ?? '', 'placeholder="Username"')
      . (isset($validation) ? $validation->showError('username', '_error_single') : '');
    echo '<br/>';

    if (!is_numeric($id)) {
      echo form_label('Sandi');
      echo 'PASS1234';
      echo '<br/>';
    }

    echo form_label('Nama');
    echo form_input('name', $name ?? '', 'placeholder="Nama"')
      . (isset($validation) ? $validation->showError('name', '_error_single') : '');
    echo '<br/>';

    echo form_label('Kelamin');
    echo form_radio('sex', 'P', $sex == 'P' ? TRUE : ($sex ? FALSE : TRUE)) . ' Pria '
      . form_radio('sex', 'W', $sex == 'W' ? TRUE : FALSE) . ' Wanita';
    echo '<br/>';

    echo form_label('Tanggal Lahir');
    echo form_input('birthday', $birthday ?? '', 'placeholder="Tanggal Lahir" class="datepicker" readonly')
      . (isset($validation) ? $validation->showError('birthday', '_error_single') : '');
    echo '<br/>';

    echo form_label('No. KTP');
    echo form_input('id_no', $id_no ?? '', 'placeholder="No. KTP"')
      . (isset($validation) ? $validation->showError('id_no', '_error_single') : '');
    echo '<br/>';
    echo '<br/>';

    echo '<span class="simap_nb">* Untuk Apoteker</span>';
    echo '<br/>';

    echo form_label('Jabatan Apoteker');
    echo form_input('pharmacist_privilege', $pharmacist_privilege ?? '', 'placeholder="Jabatan Apoteker"')
      . (isset($validation) ? $validation->showError('pharmacist_privilege', '_error_single') : '');
    echo '<br/>';

    echo form_label('No. SIPA');
    echo form_input('pharmacist_registration', $pharmacist_registration ?? '', 'placeholder="No. SIPA"')
      . (isset($validation) ? $validation->showError('pharmacist_registration', '_error_single') : '');
    echo '<br/>';

    echo form_label('Cetak Surat Pesanan Khusus ?');
    echo form_checkbox('is_prec_psyc_report', 1, $is_prec_psyc_report);
    echo '</div>';

    echo '<div class="simap_two-column">';
    echo form_label('Alamat', '', array('class' => 'simap_top'));
    echo form_textarea('address', $address ?? '', 'placeholder="Alamat"')
      . (isset($validation) ? $validation->showError('address', '_error_single') : '');
    echo '<br/>';

    echo form_label('Kota');
    echo form_input('city', $city ?? '', 'placeholder="Kota"')
      . (isset($validation) ? $validation->showError('city', '_error_single') : '');
    echo '<br/>';

    echo form_label('Telp');
    echo form_input('phone', $phone ?? '', 'placeholder="Telp"')
      . (isset($validation) ? $validation->showError('phone', '_error_single') : '');
    echo '<br/>';

    echo form_label('Akses Dashboard ?');
    echo form_checkbox('has_access_dashboard', 1, $has_access_dashboard);
    echo '<br/>';

    echo form_label('Fitur Potongan dan Diskon+ pada Penjualan ?');
    echo form_checkbox('is_sales_approval', 1, $is_sales_approval);
    echo '<br/>';

    echo form_label('Notifikasi Jatuh Tempo ?');
    echo form_checkbox('is_due_date_notification', 1, $is_due_date_notification);
    echo '<br/>';

    echo form_label('Notifikasi Pembaharuan Harga ?');
    echo form_checkbox('is_price_verification_notification', 1, $is_price_verification_notification);
    echo '</div>';
    ?>
  </div>

  <div id="simap_tab2" class="simap_tab">
    <?php
    $dropdown_option = array(0 => 'Hanya Lihat', 1 => 'Ubah', 2 => 'Akses Penuh');
    $access = '';

    foreach ($pages as $key => $sub_pages) {
      $access .= '<span>';
      if (!is_array($sub_pages)) {
        $access .= form_checkbox(
          'menu_access[]',
          $sub_pages,
          !empty($menu_access) && strpos($menu_access, $sub_pages) !== false ? true : false,
          'class="simap_main-checkbox"'
        );
        $access .= strtoupper(str_replace('_', ' ', $key)) . '&nbsp;' . form_dropdown(
          'drop_' . $sub_pages,
          $dropdown_option,
          get_access($sub_pages, $menu_access),
          'class="sub_drop"'
        ) . '<br />';
      } else {
        $access .= form_checkbox('', '', false, 'class="simap_main-checkbox"');
        $access .= strtoupper(str_replace('_', ' ', $key)) . '<br/>';
        $access .= '<span class="simap_sub-checkbox">';
        foreach ($sub_pages as $sub_page) {
          $access .= '<span>' . form_checkbox(
            'menu_access[]',
            $sub_page['value'] ?? '',
            !empty($menu_access) && strpos($menu_access, $sub_page['value']) !== false ? true : false
          );
          $access .= ucwords($sub_page['title']) . '&nbsp;' .
            (isset($sub_page['is_functional_page']) ? form_hidden('drop_' . $sub_page['value'], true) :
              form_dropdown(
                'drop_' . $sub_page['value'],
                $dropdown_option,
                get_access($sub_page['value'], $menu_access),
                'class="sub_drop"'
              )
            ) . '</span>';
        }
        $access .= '</span>';
      }
      $access .= '</span>';
    }

    $table = new \CodeIgniter\View\Table();
    $table->clear();
    $table->setTemplate(array());
    $table->addRow(array('data' => (isset($validation) ? $validation->getError('menu_access') : ''), 'class' => 'simap_left'));
    $table->addRow(array('data' => $access, 'class' => 'simap_left'));
    echo $table->generate();
    ?>
  </div>
</div>

<?php
echo '<div class="simap_action-button">';
echo form_submit('simpan', 'Simpan (F7)');
echo '<a href="' . $cancel_url . '" id="simap_btn-cancel"></a>';
echo '</div>';
echo form_close();
?>
<script type="text/javascript">
  $(function() {
    $('#simap_tabs').tabs().show();
    $(".datepicker").datepicker({
      format: 'yyyy-mm-dd',
      endDate: '+1d'
    });
    $('body').keydown(function(e) {
      var form = $('form');
      switch (e.which) {
        case (118):
          form.find('input[type="submit"]').click();
          break;
        case (120):
          $('#simap_btn-cancel').get(0).click();
          break;
      }
    });

    $('.main_drop').change(function() {
      var selected_value = $(this).find('option:selected').val();
      $(this).nextAll('span:first').find('.sub_drop option').removeAttr('selected');
      $(this).nextAll('span:first').find('.sub_drop').val(selected_value);
    });

    $('.simap_main-checkbox').each(function() {
      var parent = $(this);
      var child = parent.nextAll('.simap_sub-checkbox:first');
      if ((child.find('input:checkbox').length == child.find('input:checkbox:checked').length) &&
        (child.find('input:checkbox').length > 0))
        parent.attr('checked', true);
    });

    $('.simap_main-checkbox').click(function() {
      var ticked = $(this).prop('checked');
      $(this).nextAll('.simap_sub-checkbox:first').find('input:checkbox').each(function() {
        this.checked = ticked;
      });
    });

    $('.simap_sub-checkbox input:checkbox').click(function() {
      var ticked = $(this).prop('checked');
      var totalCheckbox = $(this).siblings('input:checkbox').length + 1;
      if (ticked) {
        var tickedSiblings = $(this).siblings('input:checkbox:checked').length + 1;
        if (tickedSiblings == totalCheckbox)
          $(this).parent().prevAll('.simap_main-checkbox:first').attr('checked', ticked);
      } else {
        $(this).parent().prevAll('.simap_main-checkbox:first').attr('checked', ticked);
      }
    });
  });
</script>