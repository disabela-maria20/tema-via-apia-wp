<?php get_header(); ?>
<?php
$products = [];
if (have_posts()) {
  while (have_posts()) {
    the_post();
    $products[] = wc_get_product(get_the_ID());
  }
}

$data = [];
$data['products'] = format_products($products);

$category = get_queried_object();
$category_description = '';
$category_image_url = '';

if ($category instanceof WP_Term) {
  $category_description = term_description($category->term_id);

  // Verifica se existe imagem na categoria
  $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
  $category_image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
}

?>

<main class="cestas-via-apia bg-categoria" style="background: <?php echo !empty($category_image_url) ? 'url(' . esc_url($category_image_url) . ')' : 'none'; ?>;">
  <div class="container">
    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
      <h1><?php woocommerce_page_title(); ?></h1>
      <?php if (!empty($category_description)) : ?>
        <div class="descricao">
          <?php echo wp_kses_post($category_description); ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
    
    <?php if (!empty($data['products'])) { ?>
      <div class="grid grid-2-sm grid-3-lg gap-32">
        <?php handel_product_list($data['products']); ?>
      </div>
      <div class="paginacao">
        <?= get_the_posts_pagination(); ?>
      </div>
      
    <?php } else { ?>
      <p class="sem-resultados">Nenhum resultado para a sua busca.</p>
    <?php } ?>
  </div>

  
</main>

<?php get_footer(); ?>
