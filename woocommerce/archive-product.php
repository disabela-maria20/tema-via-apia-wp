<?php

get_header();

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


$products = wc_get_products(array(
  'orderby' => 'name',
  'order' => 'ASC',
  'limit' => -1,
  'exclude' => $natal_products,
));

$data['product'] = format_products($products, 'product');

?>
<main class="cestas-via-apia">
  <div class="container">
    <h1>Conheça nossas Cestas</h1>
    <p>Uma seleção especial de produtos para todos os momentos.</p>

    <div class="grid grid-2-sm grid-3-lg gap-32">
      <?php handel_product_list_slide($data['product']); ?>
    </div>
  </div>

</main>

<?php get_footer(); ?>