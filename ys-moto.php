<?php
/**
 * Plugin Name:       Motorcycle store
 * Description:       Can easily create a motorcycle store systeme in your wordpress application and set multiple block to search, filter, show motorcycle you sell.
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.2.0
 * Author:            yann-soaz
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ys-moto
 * Domain Path:       /lang
 *
 * @package           yann-soaz
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
load_plugin_textdomain( 'ys-moto', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 


include 'inc/YS_Motorcylcle.php';
include 'inc/YS_Tools.php';
include 'inc/PostTypes/YS_Moto_CPT_Manager.php';

$ys_moto_plugin = new YS_Motorcycle(__DIR__);

$manager = YS_Moto_CPT_Manager::use()
  ->add_post_type(__('moto', 'ys-moto'), function (YS_Moto_CPT &$cpt) use ($ys_moto_plugin) {
    $cpt->prepare_label(__('moto', 'ys-moto'), __('motos', 'ys-moto'), true)
        ->set_description(__('Contenu permettant de gérer les motos en vente.', 'ys-moto'))
        ->set_meta('engine_size', function (YS_Moto_Meta &$meta) {
          $meta->set_description(esc_html__('Cylindrée de la moto.', 'ys-moto'))
               ->set_type('number');
        })
        ->set_meta('mileage', function (YS_Moto_Meta &$meta) {
          $meta->set_description(esc_html__('Kilométrage lors de la vente.', 'ys-moto'))
               ->set_type('number');
        })
        ->set_meta('first_registration', function (YS_Moto_Meta &$meta) {
          $meta->set_description(esc_html__('Date de mise en circulation.', 'ys-moto'))
               ->set_type('string');
        })
        ->set_meta('model', function (YS_Moto_Meta &$meta) {
          $meta->set_description(esc_html__('Modèle du véhicule.', 'ys-moto'))
               ->set_type('string');
        })
        ->set_meta('price', function (YS_Moto_Meta &$meta) {
          $meta->set_description(esc_html__('Prix du véhicule.', 'ys-moto'))
               ->set_type('number');
        })
        ->set_meta('reduced_price', function (YS_Moto_Meta &$meta) {
          $meta->set_description(esc_html__('Prix promotionnel', 'ys-moto'))
               ->set_type('number');
        })
        ->set_meta('a2', function (YS_Moto_Meta &$meta) {
          $meta->set_description(esc_html__('Permis A2', 'ys-moto'))
               ->set_type('boolean');
        })
        ->set_meta('occasion', function (YS_Moto_Meta &$meta) {
          $meta->set_description(esc_html__('Occasion', 'ys-moto'))
               ->set_type('boolean');
        })
        ->set_cat('marque', function (YS_Moto_Cat &$cat) {
          $cat->build_labels(esc_html__('marque', 'ys-moto'), esc_html__('marques', 'ys-moto'), true)
              ->isTag();
        })
        ->set_cat('moto_type', function (YS_Moto_Cat &$cat) {
          $cat->build_labels(esc_html__('type de moto', 'ys-moto'), esc_html__('types de moto', 'ys-moto'), true)
              ->isTag();
        });
    $icon = $ys_moto_plugin->get_file('img', 'motorcycle.svg');
    if ($icon) {
      $cpt->set_icon_svg($icon);
    }
  });

$ys_moto_plugin->add_init_action(function () use ($manager) {
  $manager->do_init();
});

// add_filter( 'posts_where' , 'ys_motorcycle_where', 10, 2);
// function ys_motorcycle_where( $args, $wp_query_obj ) {
//   if ($wp_query_obj->query['post_type'] === 'moto') {
//     var_dump($wp_query_obj->query);
//   }
//   return $args;
// }

add_action( 'pre_get_posts', 'ys_edit_query' );

function ys_edit_query ( $query ) {
  if ((!empty($query->query['post_type']) && $query->query['post_type'] === 'moto') && (YS_Tools::isAPI() || is_post_type_archive('moto'))) {
    $terms = [];
    $metas = [];
    if (!empty($_GET['moto_make'])) {
      $make = $_GET['moto_make'];
      $terms[] = [
        'taxonomy' => 'marque',
        'field' => 'term_id',
        'terms' => (is_array($make)) ? $make : [$make],
        'operator' => 'IN'
      ];
    }
    if (!empty($_GET['moto_cat'])) {
      $cat = $_GET['moto_cat'];
      $terms[] = [
        'taxonomy' => 'moto_type',
        'field' => 'term_id',
        'terms' => (is_array($cat)) ? $cat : [$cat],
        'operator' => 'IN'
      ];
    }
    
    if (!empty($_GET['moto_engine'])) {
      $engine = $_GET['moto_engine'];
      $metas[] = [
        'key' => 'engine_size',
        'value' => $engine,
        'compare' => '>=',
        'type' => 'NUMERIC'
      ];
    }
    if (!empty($_GET['a2'])) {
      $metas[] = [
        'key' => 'a2',
        'value' => 1,
        'compare' => '=',
      ];
    }
  }
  if (!empty($terms)) {
    $query->set('tax_query', $terms);
  }
  if (!empty($metas)) {
    $query->set('meta_query', $metas);
  }
  return;
}