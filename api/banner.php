<?php 
// Registrar Custom Post Type "Banner"
function registrar_cpt_banner()
{
  register_post_type('banner', array(
    'labels' => array(
      'name' => _x('Banners', 'Post type general name', 'textdomain'),
      'singular_name' => _x('Banner', 'Post type singular name', 'textdomain'),
      'add_new_item' => _x('Adicionar Novo Banner', 'textdomain'),
      'new_item' => _x('Novo Banner', 'textdomain'),
      'edit_item' => _x('Editar Banner', 'textdomain'),
      'view_item' => _x('Ver Banner', 'textdomain'),
    ),
    'description' => 'Gerenciar Banners com Imagem e Link',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'banner', 'with_front' => true),
    'query_var' => true,
    'supports' => array('title'),
    'publicly_queryable' => true,
  ));
}
add_action('init', 'registrar_cpt_banner');

// Registrar Metadados do Banner (Imagem e Link)
function registrar_meta_banner()
{
  $campos = array(
    'imagem', // URL ou ID do anexo da imagem do banner
    'link',   // Link associado ao banner
  );

  foreach ($campos as $campo) {
    register_post_meta('banner', $campo, array(
      'type' => 'string',
      'description' => ucfirst(str_replace('_', ' ', $campo)),
      'single' => true,
      'show_in_rest' => true, 
    ));
  }
}
add_action('init', 'registrar_meta_banner');

function banner_scheme($post)
{
  $banner = new stdClass();
  $imagem_id = get_post_meta($post->ID, 'imagem', true);
  if (is_numeric($imagem_id)) {
    $banner->imagem = wp_get_attachment_url($imagem_id); 
  } else {
    $banner->imagem = $imagem_id; 
  }
  
  $banner->link = get_post_meta($post->ID, 'link', true);
 
  return $banner;
}

// Callback da API REST para Banners
function api_banner_get($request)
{
  $q = isset($request['q']) ? sanitize_text_field($request['q']) : '';
  $page = isset($request['page']) ? absint($request['page']) : 1;
  $limit = isset($request['limit']) ? absint($request['limit']) : 20;

  $query = array(
    'post_type' => 'banner',
    'posts_per_page' => $limit,
    'paged' => $page,
    's' => $q,
  );

  $loop = new WP_Query($query);

  if ($loop->have_posts()) {
    $posts = $loop->posts;
    $total = $loop->found_posts;

    $banners = array();

    foreach ($posts as $post) {
      $banners[] = banner_scheme($post); // Adiciona o banner formatado ao array
    }

    $response = rest_ensure_response($banners);
    $response->header('X-Total-Count', $total);
    return $response;
  } else {
    return new WP_Error('no_results', 'Nenhum banner encontrado', array('status' => 404));
  }
}

// Registrar Rota da API REST para Banners
function register_api_banner_endpoints()
{
  register_rest_route('api/v1', '/banners', array(
    'methods' => 'GET',
    'callback' => 'api_banner_get',
  ));
}
add_action('rest_api_init', 'register_api_banner_endpoints');

?>