<?php

class YS_SearchForm {

  static function get_taxonomies_options (string $tax, bool $empty = false) : array {
    $terms = get_terms([
      'taxonomy' => $tax,
      'hide_empty' => !$empty,
    ]);
    $options = ['Selectionnez une option' => ''];
    foreach($terms as $term) {
      $options[$term->name] = $term->term_id;
    }
    return $options;
  }

  static function get_metas_options (string $meta_name, string $after = '', bool $isNumber = false) : array {
    global $wpdb;
    if( empty( $meta_name ) )
      return [];
    $res = $wpdb->get_col( $wpdb->prepare( "
      SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
      LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
      WHERE pm.meta_key = '%s'
      AND p.post_status = '%s'
      AND p.post_type = '%s'  ORDER BY ".($isNumber ? 'ABS(pm.meta_value)' : 'pm.meta_value')." ASC
      ", $meta_name, 'publish', 'moto' ) );
      $options = ['Selectionnez une option' => ''];
    foreach($res as $item) {
      $options[$item.' '.$after] = $item;
    }
    return $options;
  }

  static function price_range ($min, $max) {
    ?>
      <fieldset class="ys-filter-price">
    
      <div class="ys-price-field">
        <input type="range" min="<?= $min ?>" max="<?= $max ?>" value="<?= $min ?>" id="ys-price-lower">
        <input type="range" min="<?= $min ?>" max="<?= $max ?>" value="<?= $max ?>" id="ys-price-upper">
      </div>
      <div class="ys-price-wrap">
        <span class="ys-price-title"><?= __('Prix', 'ys_moto') ?> :</span>
        <div class="ys-price-wrap-1">
          <input id="ys-price-one">
          <label for="ys-price-one">€</label>
        </div>
        <div class="ys-price-wrap_line">-</div>
        <div class="ys-price-wrap-2">
          <input id="ys-price-two">
          <label for="ys-price-two">€</label>
        </div>
      </div>
    </fieldset>
  <?php
  }

  static function motor_range ($min, $max) {
    ?>
      <fieldset class="ys-filter-price">
    
      <div class="ys-price-field">
        <input type="range" min="<?= $min ?>" max="<?= $max ?>" value="<?= $min ?>" id="ys-price-lower">
        <input type="range" min="<?= $min ?>" max="<?= $max ?>" value="<?= $max ?>" id="ys-price-upper">
      </div>
      <div class="ys-price-wrap">
        <span class="ys-price-title"><?= __('Cylindrée', 'ys_moto') ?> :</span>
        <div class="ys-price-wrap-1">
          <input id="ys-price-one">
          <label for="ys-price-one">cm3</label>
        </div>
        <div class="ys-price-wrap_line">-</div>
        <div class="ys-price-wrap-2">
          <input id="ys-price-two">
          <label for="ys-price-two">cm3</label>
        </div>
      </div>
    </fieldset>
  <?php
  }

  static function term_select ($label, $name, $tax, $empty = false) {
    return self::select($label, $name, self::get_taxonomies_options($tax, $empty));
  }

  static function meta_select ($label, $name, string $meta_name, string $after = '', bool $isNumber = false) {
    return self::select($label, $name, self::get_metas_options($meta_name, $after, $isNumber));
  }

  static function select ($label, $name, $options) {
    ?>
    <div class="ys-form-field">
      <label for="<?= $name ?>"><?= $label ?></label>
      <select name="<?= $name ?>" id="<?= $name ?>">
        <?php foreach ($options as $item => $value) : ?>
          <option <?= (!empty($_GET[$name]) && $_GET[$name] == $value) ? 'selected' : '' ?> value="<?= $value ?>"><?= $item ?></option>
        <?php endforeach; ?>
      </select>
    </div>
        <?php
  }

  static function choices ($label, $name, $options, $multiple = false) {
    $field = ($multiple) ? 'checkbox' : 'radio'
    ?>
      <p><?= $label ?></p>
      <?php foreach ($options as $item => $value) : ?>
        <input type="<?= $field ?>" name="<?= $name ?><?= ($multiple) ? '[]' : '' ?>" id="<?= $name ?>-<?= sanitize_title($item) ?>" value="<?= $value ?>"/>
        <label for="<?= $name ?>-<?= sanitize_title($item) ?>"><?= $item ?></label>
      <?php endforeach; ?>
    <?php
  }

  static function choice ($label, $name) {
    ?>
      <input type="checkbox" <?= (!empty($_GET[$name])) ? 'checked' : '' ?> name="<?= $name ?>" id="<?= $name ?>" value="1"/>
      <label for="<?= $name ?>"><?= $label ?></label>
    <?php
  }
}