<?php
function format_products($products, $img_size = 'medium')
{
  $products_final = [];
  foreach ($products as $product) {
    $categories = wp_strip_all_tags(wc_get_product_category_list($product->get_id(), ', '));

    $products_final[] = [
      'name' => $product->get_name(),
      'price' => $product->get_price_html(),
      'link' => $product->get_permalink(),
      'img' => wp_get_attachment_image_src($product->get_image_id(), $img_size)[0],
      'categories' => $categories,
    ];
  }
  return $products_final;
}

function handel_product_list($products)
{ ?>
  <?php foreach ($products as $product) { ?>
    <a class="product-item" href="<?= esc_url($product['link']); ?>">
      <div class="products-list">
        <div class="product-info">
          <img src="<?= esc_url($product['img']); ?>" alt="<?= esc_attr($product['name']); ?>">
          <h3><?= esc_html($product['name']); ?></h3>
          <p class="product-categories"><?= esc_html($product['categories']); ?></p> 
        </div>
        <div class="product-overlay">
          <?= $product['price']; ?>
          <button class="btn-link">Ver Mais</button>
        </div>
      </div>
    </a>
  <?php } ?>
<?php
} 

function handel_product_list_slide($products)
{ ?>
  <?php foreach ($products as $product) { ?>
    <div class="splide__slide">
      <a class="product-item" href="<?= esc_url($product['link']); ?>">
        <div class="products-list">
          <div class="product-info">
            <img src="<?= esc_url($product['img']); ?>" alt="<?= esc_attr($product['name']); ?>">
            <h3><?= esc_html($product['name']); ?></h3>
            <p class="product-categories"><?= esc_html($product['categories']); ?></p> 
          </div>
          <div class="product-overlay">
            <?= $product['price']; ?>
            <button class="btn-link">Ver Mais</button>
          </div>
        </div>
      </a>
    </div>
  <?php } ?>
<?php
}
