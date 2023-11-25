<div id="simap_header">
  <?= $menu['html_access'] ?? '' ?>
  <span id="simap_status">
    <span id="simap_user" title="<?= session()->get('simap_user') ?>">
      <?= session()->get('simap_user') ?>
    </span>

    <span id="simap_notifications-container">
      <span id="simap_option"><span class="fa fa-2x fa-cog"></span>
        <ul>
          <li><a href="<?= base_url('logout') ?>">Keluar</a></li>
        </ul>
      </span>
    </span>
  </span>
</div>
<script type="text/javascript">
  $('body').on('click tap', '#simap_menu > li', function(event) {
    $('#simap_menu > li').not(this).removeClass('active');
    $('#simap_notifications-container > span').removeClass('active');
    $(this).toggleClass('active').find('ul')
      .css({
        left: $(this).offset().left + 15
      });
    event.stopPropagation();
  });

  $('body').on('click tap', '#simap_notifications-container > span', function(event) {
    if ($(this).data('url') != undefined)
      $(this).find('li').load('<?= base_url('global_data/get_') ?>' + $(this).data('url'));
    $('#simap_notifications-container > span').not(this).removeClass('active');
    $('#simap_menu > li').removeClass('active');
    $(this).toggleClass('active');
    event.stopPropagation();
  });

  $(document).on('mouseup', function(event) {
    if (!$(event.target).is('a') && (event.which == 1 || event.button == 1)) {
      $('#simap_menu > li').removeClass('active');
      $('#simap_notifications-container > span').removeClass('active');
    }
  });

  $(function() {});
</script>