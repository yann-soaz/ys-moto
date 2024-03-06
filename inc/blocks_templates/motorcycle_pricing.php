<div <?= get_block_wrapper_attributes() ?>>
  <span class="price<?= ((!empty($reduced_price) && $reduced_price != 0) ? ' has-reduced' : '') ?>">
    <?= (!empty($price)) ? $price : '-' ?> €  
  </span>
  <?php if ((!empty($reduced_price) && $reduced_price != 0)) : ?>
    <span class="reduced-price">
      <?= $reduced_price ?> €
    </span>
  <?php endif; ?>
</div>