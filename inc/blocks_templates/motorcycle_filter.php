<form class="ys-moto-filter" method="GET" action="<?= get_post_type_archive_link('moto') ?>">
  <?= YS_SearchForm::term_select('Type de moto', 'moto_cat', 'moto_type') ?> 
  <?= YS_SearchForm::term_select('Marque', 'moto_make', 'marque') ?> 
  <?= YS_SearchForm::meta_select('Cylindrée minimum', 'moto_engine', 'engine_size') ?> 
  <?= YS_SearchForm::choice('Permis A2', 'a2') ?> 
  <div class="wp-block-button">
    <button type="submit" class="wp-block-button__link wp-element-button" style="padding-top:0;padding-bottom:0">Rechercher</button>
  </div>
</form>