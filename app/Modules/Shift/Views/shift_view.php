<script type="text/javascript" src="<?= base_url('files/js/simap_tablesorter.js'); ?>"></script>

<?php
function two_digit($val)
{
  return ((strlen($val) <= 1) ? '0' : '') . $val . '.00';
}

$tmpl = array(
  'table_open' => '<table cellpadding="4" cellspacing="0" class="simap_table-data">'
);
$table = new \CodeIgniter\View\Table($tmpl);

echo '<div class="simap_table-data-container">';

$headings = array('No', 'Jam Kerja', 'Hapus');
$table->setHeading($headings);

$counter = $pagination['last_no'];

if (!empty($data)) {
  $delete_msg = "Apakah anda yakin untuk menghapus data $title ?";

  foreach ($data->getResult() as $row) {
    $edit_url = base_url("$module/edit/$row->id");
    $delete_url = base_url("$module/delete/$row->id");

    $table->addRow(array(
      ++$counter,
      "<a href='$edit_url' class='fa fa-pen'>" . two_digit($row->start_time) . " - " . two_digit($row->end_time) . "</a>",
      "<a href='$delete_url' class='fa fa-trash' onclick='return confirm(\"$delete_msg\")'></a>",
    ));
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
  });
</script>