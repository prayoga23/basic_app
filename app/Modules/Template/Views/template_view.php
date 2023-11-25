<html lang="en">
<?= view('Modules\Header\Views\header_view'); ?>

<body>
  <?= view("Modules\Template\Views\menu_view"); ?>
  <div id="simap_wrapper">
    <div class="simap_message">
      <?php if (session()->getFlashdata('success_message')) { ?>
        <div class="simap_success">
          <?= session()->getFlashdata('success_message'); ?>
        </div>
      <?php } else if (session()->getFlashdata('error_message')) { ?>
        <div class="simap_error">
          <?= session()->getFlashdata('error_message'); ?>
        </div>
      <?php } ?>
    </div>
    <div id="simap_title">
      <?= $title ?>
    </div>
    <div id="simap_form">
      <?php
      echo view("Modules\Template\Views\\filter_view");
      echo view("Modules\\" . ucfirst($module) . "\Views\\$view_file");
      ?>
    </div>
    <div id="simap_shift-version">
      <span id="simap_version">
        1.0.0
      </span>
      <span id="simap_shift" class="fa fa-clock-o">
        <?= session()->get('simap_shift') ?>
      </span>
    </div>
  </div>
  <div id="simap_message-status"></div>
  <script type="text/javascript">
    history.pushState(null, null, location.href);
    window.onpopstate = function() {
      history.go(1);
    };
    var is_active_tab = true;
    $(window).on("blur focus", function(e) {
      var prevType = $(this).data("activeState");

      if (prevType != e.type) { //  reduce double fire issues
        switch (e.type) {
          case "blur":
            is_active_tab = false;
            break;
          case "focus":
            is_active_tab = true;
            break;
        }
      }

      $(this).data("activeState", e.type);
    });
    $(function() {
      $('#simap_wrapper').css('margin-top', $('#simap_header').outerHeight() +
        (($(window).width() < 700) ? 60 : 10));
      $('.simap_action-button:not(.partial-loaded)').css('top', $('#simap_header').outerHeight());
      $(".simap_success").delay(5000).slideUp();
      $(".simap_error").delay(5000).slideUp();
      $('.simap_error-icon').each(function() {
        var html = $(this).html();
        $(this).html('');
        $(this).attr('title', html);
      });
      $('body').on('click tap', '.simap_error-icon', function() {
        $('.title').not($(this).find(".title")).remove();
        var $title = $(this).find(".title");
        if (!$title.length) {
          var element_left = parseFloat($(this).offset().left);
          var additional_style = 'left: 0;';
          var window_width = $(document).width();
          if (element_left >= (window_width * .75))
            additional_style = 'right: 0;';
          else if (element_left > (window_width * .25) &&
            element_left < (window_width * .75))
            additional_style = 'left:50%;transform:translateX(-50%);';
          $(this).append('<span class="title" style="' + additional_style +
            '">' + $(this).attr("title") + '</span>');
        } else {
          $title.remove();
        }
      });

      <?php if ($is_active_session) { ?>
        var active_session = setInterval(function() {
          $.ajax({
            method: 'get',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            },
            url: '<?= base_url('utility/is_active_session') ?>',
            success: function(is_active) {
              if (!is_active) {
                overtime();
                clearInterval(active_session);
              }
            }
          });
        }, 30000);
      <?php } else { ?>
        overtime();
      <?php } ?>

      function overtime() {
        $('#simap_shift').addClass('overtime').prop('title', 'Jam Kerja berakhir atau salah Jam Kerja');
      }

      $('body').on('click tap', '.simap_help-icon', function() {
        $('.simap_help-icon').not(this).removeClass('active');
        $(this).toggleClass('active');
      });

      $('body').on('click', '.simap_message-close', function() {
        $('#simap_message-status').removeClass('active');
        clearTimeout();
      });

    });
  </script>
</body>

</html>