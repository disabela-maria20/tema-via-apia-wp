<?php get_header(); ?>

<?php
function format_single_product($id, $img_size = 'medium')
{
  $product = wc_get_product($id);

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

<div class="container">
  <main class="produto">
    <?php
    if (have_posts()) {
      while (have_posts()) {
        the_post();
        $produto = format_single_product(get_the_ID());
    ?>
        <div class="product-gallery" data-gallery="gallery">
          <div class="product-gallery-list">
            <?php foreach ($produto['gallery'] as $img) { ?>
              <img data-gallery="list" src="<?= $img; ?>" alt="<?= $produto['name']; ?>">
            <?php } ?>
          </div>
          <div class="produto-gallery-main">
            <img data-gallery="main" src="<?= $produto['img']; ?>" alt="<?= $produto['name']; ?>">
          </div>
        </div>

        <div class="product-detail">
          <small><?= $produto['sku']; ?></small>
          <h1><?= $produto['name']; ?></h1>
          <p class="product-categories"><?= $produto['categories']; ?></p>
          <p class="product-price"><?= $produto['price']; ?></p>
          <?php $fields = CFS()->get('cometario'); ?>
          <?php if ($fields) { ?>
            <div class="product-comments">
              <h3>Comentários</h3>
              <section class="splide" id="comentarios" aria-label="Splide Basic HTML Example">
                <div class="splide__track">
                  <ul class="splide__list">
                    <?php if ($fields) { ?> <?php foreach ($fields as $field) { ?>
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
        <?php }
                  } ?>
        <div class="cta">
          <a class="cta-comprar" href="#">Comprar</a>
        </div>
        </div>
</div>
<?php }
    } ?>
</main>
<?php
    if (have_posts()) {
      while (have_posts()) {
        the_post();
        $produto = format_single_product(get_the_ID());
    ?>

    <section>
      <div class="constainer">

      </div>
    </section>
 <?php }
                  } ?>
<?php
$related_ids = wc_get_related_products($produto['id'], 6);
$related_products = [];
foreach ($related_ids as $product_id) {
  $related_products[] = wc_get_product($product_id);
}
$related = format_products($related_products);
?>

<section class="relacinados">
  <div class="container">
    <h2 class="subtitulo">Relacionados</h2>
    <div class="grid grid-3-lg gap-32">
      <?php handel_product_list($related); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>