<?php get_header(); ?>

<?php

function format_single_product($id, $img_size = 'medium')
{
  $product = wc_get_product($id);

  if (!$product) {
    return null; // Retorna null se não encontrar o produto
  }

  $gallery_ids = $product->get_gallery_attachment_ids();
  $gallery = [];
  if ($gallery_ids) {
    foreach ($gallery_ids as $img_id) {
      $gallery[] = wp_get_attachment_image_src($img_id, $img_size)[0];
    }
  }
  $categories = wc_get_product_category_list($id);

  return [
    'id' => $id,
    'name' => $product->get_name(),
    'price' => $product->get_price_html(),
    'link' => $product->get_permalink(),
    'sku' => $product->get_sku(),
    'description' => $product->get_description(),
    'img' => wp_get_attachment_image_src($product->get_image_id(), $img_size)[0],
    'gallery' => $gallery,
    'categories' => $categories,
  ];
}
?>

<?php
$product_id = get_the_ID();
$product = wc_get_product($product_id);
$categories = $product->get_category_ids();
$category_image_url = '';

if (!empty($categories)) {
  $category = get_term($categories[0], 'product_cat');
  $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
  $category_image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
}
?>

<section style="background: url(<?php echo esc_url($category_image_url); ?>);">
  <div class="container">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <?php
        $produto = format_single_product(get_the_ID());
        if ($produto):
        ?>
          <main class="produto">
            <div class="product-gallery" data-gallery="gallery">
              <div class="product-gallery-list">
                <?php foreach ($produto['gallery'] as $img) { ?>
                  <img data-gallery="list" src="<?= esc_url($img); ?>" alt="<?= esc_attr($produto['name']); ?>">
                <?php } ?>
              </div>
              <div class="produto-gallery-main">
                <img data-gallery="main" src="<?= esc_url($produto['img']); ?>" alt="<?= esc_attr($produto['name']); ?>">
              </div>
            </div>

            <div class="product-detail">
              <small><?= esc_html($produto['sku']); ?></small>
              <h1><?= esc_html($produto['name']); ?></h1>
              <p class="product-categories"><?= $produto['categories']; ?></p>
              <p class="product-price"><?= $produto['price']; ?></p>
              <?php $fields = CFS()->get('cometario'); ?>
              <?php if ($fields) { ?>
                <div class="product-comments">
                  <h3>Comentários</h3>
                  <section class="splide" id="comentarios" aria-label="Splide Basic HTML Example">
                    <div class="splide__track">
                      <ul class="splide__list">
                        <?php foreach ($fields as $field) { ?>
                          <li class="splide__slide">
                            <div class="comments">
                              <p><?php echo esc_html($field['comentario_feito']); ?></p>
                              <h4>
                                <i class="bi bi-chat-right-fill"></i>
                                <span><?php echo esc_html($field['nome']); ?></span>
                              </h4>
                            </div>
                          </li>
                        <?php } ?>
                      </ul>
                    </div>
                  </section>
                </div>
              <?php } ?>
              <div class="cta">
                <a class="cta-comprar" href="#">Comprar</a>
              </div>
            </div>
          </main>
          <!-- Ajustando o estilo da linha horizontal com base na categoria -->
          <hr style="border: 1px solid <?= $produto['categories'] = 'cesta-de-natal' ? '#8C0000': '#ccc'; ?>;">
          <section class="descricao">
            <div class="grid">
              <?php $fields = CFS()->get('item'); ?>
              <ul class="item">
                <?php if ($fields) { ?>
                  <?php foreach ($fields as $field) { ?>
                    <li><?php echo esc_html($field['item_da_cesta']); ?></li>
                  <?php } ?>
                <?php } ?>
              </ul>
              <p><?php echo CFS()->get('informacao'); ?></p>
            </div>
          </section>
        <?php else: ?>
          <p>Produto não encontrado.</p>
        <?php endif; ?>
      <?php endwhile; ?>
    <?php endif; ?>

    <?php
    if (isset($produto)) {
      $related_ids = wc_get_related_products($produto['id'], 6);
      $related_products = [];
      foreach ($related_ids as $product_id) {
        $related_products[] = wc_get_product($product_id);
      }
      $related = format_products($related_products);
    }
    ?>

    <section class="relacinados">
      <div class="container">
        <h2 class="subtitulo">Relacionados</h2>
        <div class="grid grid-3-lg gap-32">
          <?php handel_product_list($related); ?>
        </div>
      </div>
    </section>
  </div>
</section>

<?php get_footer(); ?>
