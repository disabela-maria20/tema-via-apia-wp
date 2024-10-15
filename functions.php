<?php

include(get_template_directory() . '/inc/product-list.php');
include(get_template_directory() . "/api/banner.php");

function handel_add_woocommerce_support() {
  add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'handel_add_woocommerce_support');

function wpb_image_editor_default_to_gd($editors){
  $gd_editor = 'WP_Image_Editor_GD';
  $editors = array_diff($editors, array($gd_editor));
  array_unshift($editors, $gd_editor);
  return $editors;
}
add_filter('wp_image_editors', 'wpb_image_editor_default_to_gd');


function use_scripts(){
  wp_enqueue_style('base-style', get_template_directory_uri() . '/assets/css/base.min.css', array(), '1.0.0', 'all');
  wp_enqueue_style('adobe-fonts', 'https://use.typekit.net/ixb0opo.css', array(), null, 'all');
  wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css', array(), null, 'all');
  
  wp_enqueue_script('vue-script', "https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js", array(), null, true);
  wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/main.js', array(), null, true);
  wp_enqueue_script('splide', get_template_directory_uri() . '/assets/js/lib/splide.min.js', array('jquery'), '2.3.4', true);

  wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'use_scripts');



function add_meta_tags()
{
  echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
}
add_action('wp_head', 'add_meta_tags');

add_theme_support('menus');

function register_my_menu()
{
  register_nav_menu('menu-principal', __('Menu Principal'));
  register_nav_menu('menu-rodape', __('Menu Rodape'));
}
add_action('init', 'register_my_menu');

function excluir_cestas_de_natal_da_loja($query) {
  if (!is_admin() && $query->is_main_query() && is_shop()) {
    $categoria_excluida = 'cestas-de-natal';
    $tax_query = array(
      array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug', 
        'terms'    => $categoria_excluida,
        'operator' => 'NOT IN', 
      ),
    );

    $query->set('tax_query', $tax_query);
  }
}
add_action('pre_get_posts', 'excluir_cestas_de_natal_da_loja');

