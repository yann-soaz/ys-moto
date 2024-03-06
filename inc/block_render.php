<?php
include "Search/YS_SearchForm.php";

function get_motorcycle_metas_array () : array { 
  return [
    'marque' => [esc_html__('Marque', 'ys-moto'), 'tax'],
    'model' => [esc_html__('Modèle', 'ys-moto'), 'meta'],
    'moto_type' => [esc_html__('Type', 'ys-moto'), 'tax'],
    'engine_size' => [esc_html__('Cylindrée', 'ys-moto'), 'meta'],
    'first_registration' => [esc_html__('Mise en circulation', 'ys-moto'), 'meta'],
    'mileage' => [esc_html__('Kilométrage', 'ys-moto'), 'meta'],
  ];
}

function render_motorcycle_metas (array $attributes) {
  $is_grid = ($attributes['presentation'] === 'grid');
  ob_start();
    include 'blocks_templates/motorcycle_metas.php';
  $result = ob_get_clean();
  return $result;
}

function get_motorcycle_metas (string $meta_name) : string {
  $MOTOCYCLE_METAS = get_motorcycle_metas_array();
  if (!empty($MOTOCYCLE_METAS[$meta_name])) {
    switch ($MOTOCYCLE_METAS[$meta_name][1]) {
      case 'meta' :
        $meta = get_post_meta(get_the_ID(), $meta_name, true);
        return (empty($meta)) ? '-' : $meta;
      break;
      case 'tax' :
        $terms = get_the_terms(get_the_ID(), $meta_name);
        if (empty($terms))
          return '-';
        $res = '';
        for($i = 0; $i < sizeof($terms); $i++) {
          $res .= $terms[$i]->name.(($i + 1 < sizeof($terms)) ? ', ' : '');
        }
        return $res;
      break;
    }
  }
  return '-';
}

function render_motorcycle_pricing () {
  $price = get_post_meta(get_the_ID(), 'price', true);
  $reduced_price = get_post_meta(get_the_ID(), 'reduced_price', true);

  ob_start();
    include 'blocks_templates/motorcycle_pricing.php';
  return ob_get_clean();
}

function render_form_filter (array $attributes) {
  ob_start();
    include 'blocks_templates/motorcycle_filter.php';
  return ob_get_clean();
}

return [
  'motorcycle_metas' => 'render_motorcycle_metas',
  'motorcycle_pricing' => 'render_motorcycle_pricing',
  'motorcycle_filter' => 'render_form_filter'
];