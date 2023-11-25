<script type="text/javascript" src="<?= base_url('files/js/bootstrap-datepicker.js') ?>"></script>

<div id="simap_top-navigation">
  <?php
    if (isset($is_list_view)) {
      echo form_open("{$module}/" . (isset($search_url) ? $search_url : 'search'), array('method' => 'get'));
        echo '<div id="simap_filter-container">';
          echo '<div>';
            echo form_label('Kata Kunci');
            echo form_input('keyword', (isset($keyword) ? $keyword : ''), 'placeholder="Kata Kunci"');
          echo '</div>';

          if (!empty($date_filter)) {
            foreach ($date_filter as $date) {
              echo '<div>';
                echo form_label("Tanggal {$date['display_name']}");
                echo form_input("{$date['name']}[]", $date['from'], "placeholder='Tanggal". (isset($date['to']) ? ' Mulai' : '') ."' class='datepicker' id='{$date['name']}-from' readonly");

                if (isset($date['to'])) {
                  echo ' - ';
                  echo form_input("{$date['name']}[]", $date['to'], "placeholder='Tanggal Akhir' class='datepicker' id='{$date['name']}-to' readonly");
                }
              echo '</div>';
            }
          }

          if (!empty($foreign_filter)) {
            foreach ($foreign_filter as $filter) {
              echo '<div>';
                echo form_label($filter['display_name'], $filter['name']);
                echo form_dropdown($filter['name'], $filter['options'], $filter['value']) . '<br/>';
              echo '</div>';
            }
          }
        echo '</div>';

        echo form_submit('submit', 'Cari');
      echo form_close();
    }

    if (isset($is_list_view)) {
      echo '<hr/>';
    }
  ?>
  
  <div>
    <?php
    if (isset($is_creating)) { ?>
      <a href="<?= base_url("{$module}/" . (isset($add_url) ? $add_url : 'add')) ?>" id="simap_btn-add" class="simap_button"> (F2)</a>
    <?php }
    if (isset($additional_buttons)) {
      foreach ($additional_buttons as $button) {
        echo "&nbsp; {$button}";
      }
      if (isset($help_info)) { ?>
        <span class="simap_help-icon">
          <div>
            <?= $help_info ?>
          </div>
        </span>
      <?php } ?>
    <?php } ?>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    $('body').on('focus', '.datepicker', function() {
      $(this).datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
      });
    });
  });
</script>