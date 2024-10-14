<?php

get_header();

// Verifica se está na página de arquivo da tag "natal"
$is_natal_page = is_tax('product_tag', 'natal');
$natal_tag_id = get_term_by('slug', 'natal', 'product_tag')->term_id;

// Obtém os IDs dos produtos que têm a tag 'natal'
$natal_products = get_posts(array(
  'post_type' => 'product',
  'posts_per_page' => -1,
  'tax_query' => array(
    array(
      'taxonomy' => 'product_tag',
      'field' => 'id',
      'terms' => $natal_tag_id,
    ),
  ),
  'fields' => 'ids',
));


// Se está na página de "natal", mostra produtos com a tag "natal"
if ($is_natal_page) {
  $products = wc_get_products(array(
    'orderby' => 'name',
    'order' => 'ASC',
    'limit' => -1,
    'include' => $natal_products,
  ));
} elseif (is_product_category()) {
  // Se está em uma página de categoria de produto, exclui produtos da tag "natal"
  echo get_queried_object() .'tetse';
  $category = get_queried_object();
  $products = wc_get_products(array(
    'orderby' => 'name',
    'order' => 'ASC',
    'limit' => -1,
    'category' => array($category->slug),
    'exclude' => $natal_products,
  ));
} else {
  // Se está na página de "Cestas" (ou seja, não é tag "natal" nem categoria específica)
  $products = wc_get_products(array(
    'orderby' => 'name',
    'order' => 'ASC',
    'limit' => -1,
    'exclude' => $natal_products,
  ));
}

$data['product'] = format_products($products, 'product');
?>

<main class="cestas-via-apia">
  <div class="container">
    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
      <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
    <?php endif; ?>

    <?php if ($is_natal_page) : ?>
      <p>Veja nossa seleção especial de produtos de Natal!</p>
    <?php else : ?>
      <p>Uma seleção especial de produtos para todos os momentos.</p>
    <?php endif; ?>

    <div class="grid grid-2-sm grid-3-lg gap-32">
      <?php handel_product_list_slide($data['product']); ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>
