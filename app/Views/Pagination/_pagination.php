<?php $pager->setSurroundCount(1); ?>

<span id="simap_pagination-links">
  <?php if ($pager->hasPrevious()) : ?>
    <a href="<?= $pager->getFirst(); ?>">&lt;&lt;</a>
  <?php endif; ?>

  <?php foreach ($pager->links() as $link) : ?>
    <?php if ($link['active']) : ?>
      <strong><?= $link['title'] ?></strong>
    <?php else : ?>
      <a href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
    <?php endif; ?>
  <?php endforeach; ?>

  <?php if ($pager->hasNext()) : ?>
    <a href="<?= $pager->getLast(); ?>">&gt;&gt;</a>
  <?php endif; ?>
</span>