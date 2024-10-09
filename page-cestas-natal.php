<?php
// Template Name: cestas natal
get_header();

$natal_tag_id = get_term_by('slug', 'natal', 'product_tag')->term_id;

$products = wc_get_products(array(
  'orderby' => 'name',
  'order' => 'ASC',
  'tag' => "natal", 
  'limit' => -1 
));

$data = [];
$data['product'] = format_products($products, 'product');

// echo '<pre>';
// print_r($products);
// echo '<pre/>';

?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="cesta-natal-via-apia" style="background: url('<?php echo CFS()->get('imagem-bg-natal'); ?>');">
      <div class="container">
        <h1>Cestas de Natal</h1>
        <p>Uma seleção especial de produtos para todos os momentos.</p>

        <div class="grid grid-2-sm grid-3-lg gap-32">
          <?php handel_product_list($data['product']); ?>
        </div>
      </div>
    </main>
<?php endwhile; else: endif; ?>

<?php get_footer(); ?>
