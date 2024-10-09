<?php
// Template Name: cestas
get_header();

$natal_tag_id = get_term_by('slug', 'natal', 'product_tag')->term_id;

$products = wc_get_products(array(
    'orderby' => 'name',
    'order' => 'ASC',
    'limit' => -1 
));

$filtered_products = array_filter($products, function($product) use ($natal_tag_id) {
    $product_tags = wp_get_post_terms($product->get_id(), 'product_tag', array('fields' => 'ids'));
    return !in_array($natal_tag_id, $product_tags);
});

$data = [];
$data['product'] = format_products($filtered_products, 'product');


?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="cestas-via-apia">
      <div class="container">
        <h1>Conheça nossas Cestas</h1>
        <p>Uma seleção especial de produtos para todos os momentos.</p>

        <div class="grid grid-2-sm grid-3-lg gap-32">
          <?php handel_product_list_slide($data['product']); ?>
        </div>
      </div>

    </main>
<?php endwhile; else: endif; ?>

<?php get_footer(); ?>
