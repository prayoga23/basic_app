<script type="text/javascript" src="<?= base_url('files/js/simap_tablesorter.js'); ?>"></script>

<?php
$tmpl = array(
  'table_open' => '<table cellpadding="4" cellspacing="0" class="simap_table-data">'
);
$table = new \CodeIgniter\View\Table($tmpl);

echo '<div class="simap_table-data-container">';

$headings = array('No', 'Username', 'Nama', 'Kelamin', 'Alamat', 'Kota', 'Telp', 'Login Terakhir', 'Hapus');
$table->setHeading($headings);

$counter = $pagination['last_no'];

if (!empty($data)) {
  $delete_msg = "Apakah anda yakin untuk menghapus data $title ?";

  foreach ($data->getResult() as $row) {
    $edit_url = base_url("$module/edit/$row->id");
    $delete_url = base_url("$module/delete/$row->id");

    $array_row = array(
      ++$counter,
      "<a href='$edit_url' class='fa fa-pen'>$row->username</a>",
      $row->name,
      ($row->sex == 'P' ? 'Pria' : 'Wanita'),
      $row->address,
      $row->city,
      $row->phone,
      !isset($row->last_login) ? '' : date('d-m-Y H:i:s', strtotime($row->last_login)),
      ''
    );

    // reset & delete button
    if ($status_access)
      $array_row[sizeof($array_row) - 1] =
        ($row->id != 1 && $row->is_new_password ? "<a href='javascript:void(0)' class='fa fa-redo' title='Reset Sandi' data-value='$row->username'></a>" : '') .
        ($row->is_super_admin ? '' : "<a href='$delete_url' class='fa fa-trash' onclick='return confirm(\"$delete_msg\")'></a>");

    $table->addRow($array_row);
  }
} else {
  $table->addRow(array(array('data' => 'Tidak ada data ditemukan', 'colspan' => count($headings))));
}

echo $table->generate();

echo '</div>';
echo '<div id="simap_table-footer">';
echo '<span>Menampilkan data ' . (($counter > 0) ? number_format($pagination['last_no'] + 1) : 0) . ' - ' . number_format($counter) .
  ' dari <b>' . number_format($pagination['total_rows']) . '</b> data.</span>';
echo $pagination['links'];
echo '</div>';
?>

<div id="simap_confirmation-box-container">
  <div id="simap_confirmation-box-wrapper">
    <div id="simap_confirmation-box">
      <div>
        <p>Ketik "<b>OK</b>" untuk mereset</p>
        <h4>Sandi karyawan</h4>
        <p><?= form_input('confirmation', '', 'placeholder="OK" id="simap_confirmation"') ?></p>
        <?= form_button('submit', 'Reset', 'id="simap_reset-button"') ?>
        <a href="javascript:void(0)" class="simap_close-dialog simap_button">Batal</a>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    $('table').tablesorter();
    $('body').keydown(function(e) {
      switch (e.which) {
        case (113):
          $('#simap_btn-add').get(0).click();
          break;
      }
    });

    var user = '';
    $('body').on('click', '.simap_close-dialog', function() {
      $('#simap_confirmation-box-container').removeClass('shown');
      user = '';
      $('#simap_confirmation').val('');
    });

    $('body').on('click', '#simap_reset-button', function() {
      if ($('#simap_confirmation').val().toUpperCase() == 'OK')
        window.location = '<?= base_url($module); ?>/reset_password/' + user;
      else {
        alert('Masukkan teks yang tampil');
      }
    });

    $('#simap_form .simap_table-data tbody').on('click', '.fa-redo', function(event) {
      event.stopPropagation();
      $('#simap_confirmation-box-container').addClass('shown');
      user = $(this).data('value');
    });
  });
</script>