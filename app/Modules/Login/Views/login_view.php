<html lang="en">
<?php echo view('Modules\Header\Views\header_view'); ?>

<body>
  <div id="simap_wrapper">
    <div id="simap_login">
      <div id="simap_title">
        <img src="<?php echo base_url('files/img/properties/simap_logo.png') ?>" id="simap_logo" />
      </div>
      <div id="simap_form">
        <?php if (session()->getFlashdata('error_message')) { ?>
          <div class="simap_message">
            <div class="simap_error">
              <?php echo session()->getFlashdata('error_message'); ?>
            </div>
          </div>
        <?php }
        echo form_open('login/validate_login');

        echo form_hidden('callback_url', $callback_url ?? '');

        echo form_input('uname', '', 'placeholder="Username"') . (isset($validation) ? $validation->showError('uname', '_error_single') : '');
        echo '<br />';

        echo form_password('pass', '', 'placeholder="Password"') . (isset($validation) ? $validation->showError('pass', '_error_single') : '');
        echo '<br />';

        echo form_label('Jam Kerja') . '<br/>';
        echo form_dropdown('shift_id', $shift, $shift_id);
        echo '<br />';

        echo form_submit('submit', 'Masuk');

        echo form_close();
        ?>
      </div>
      <div id="simap_footer">
        <div id="rus">
          <a href="http://reachusolutions.com">
            <img src="<?php echo base_url('files/img/properties/logo.png') ?>" alt="Reach U Solutions" title="Reach U Solutions" />
          </a>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $(function() {
      $("#simap_error").delay(5000).slideUp();
      $('.simap_error-icon').each(function() {
        var html = $(this).html();
        $(this).html('');
        $(this).attr('title', html);
      });
      $('body').on('click tap', '.simap_error-icon', function() {
        $('.title').not($(this).find(".title")).remove();
        var $title = $(this).find(".title");
        if (!$title.length) {
          var additional_style = 'left: 0;';
          var window_width = $(document).width();
          if ($(this).offset().left >= (window_width * .75))
            additional_style = 'right: 0;';
          else if ($(this).offset().left > (window_width * .25) &&
            $(this).offset().left < (window_width * .75))
            additional_style = 'left:50%;transform:translateX(-50%);';
          $(this).append('<span class="title" style="' + additional_style +
            '">' + $(this).attr("title") + '</span>');
        } else {
          $title.remove();
        }
      });
    });
  </script>
</body>

</html>