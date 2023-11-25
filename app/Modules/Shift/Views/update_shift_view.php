<?php
$confirm_msg = 'Apakah anda yakin untuk ' . (is_numeric($id) ? 'mengubah' : 'menambah') . ' data Jam Kerja ?';
echo form_open("$module/submit", array('onsubmit' => "return confirm('$confirm_msg')"));
if (is_numeric($id)) {
  echo form_hidden('id', $id);
}

echo form_label('Jam Kerja *');
echo form_dropdown('start_time', $list_time, $start_time) .
  (isset($validation) ? $validation->showError('start_time', '_error_single') : '');
echo ' - ';
echo form_dropdown('end_time', $list_time, $end_time) .
  (isset($validation) ? $validation->showError('end_time', '_error_single') : '');
echo '<br/>';

echo '<span class="simap_nb">(</span>*<span class="simap_nb">) harus diisi.</span><br /><br />';

echo '<div class="simap_action-button">';
echo form_submit('simpan', 'Simpan (F7)');
echo '<a href="' . $cancel_url . '" id="simap_btn-cancel"></a>';
echo '</div>';
echo form_close();
?>
<script type="text/javascript">
  $(function() {
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
  });
</script>