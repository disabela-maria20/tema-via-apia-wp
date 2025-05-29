<?php
// Template Name: home
get_header();

$products_slide = wc_get_products(array('orderby' => 'name', 'order' => 'ASC', 'tag' => 'slide'));

$data = [];
$data['slide'] = format_products($products_slide, 'slide');

?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>

<section class="splide" id="banner">
  <div class="splide__track">
    <ul class="splide__list">
      <?php
          $args = array(
            'post_type' => 'banner',
            'posts_per_page' => -1,
          );
          echo var_dump($args);
          
          $query = new WP_Query($args);

          if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();

              $imagem = wp_get_attachment_url(get_post_meta(get_the_ID(), 'imagem', true));
              $link = get_post_meta(get_the_ID(), 'link', true);

              if ($imagem) :
          ?>
      <li class="splide__slide">
        <a href="<?php echo esc_url($link); ?>" target="_blank">
          <img src="<?php echo esc_url($imagem); ?>" alt="<?php the_title(); ?>">
        </a>
      </li>
      <?php
              endif;
            endwhile;
            wp_reset_postdata();
          else :
            echo '<p>Nenhum banner encontrado.</p>';
          endif;
          ?>
    </ul>
  </div>
</section>

<section class="slide-procutos">
  <div class="container">
    <div>
      <h2 class="titulo"><?php echo CFS()->get('titulo_slide_cestas'); ?></h2>
      <p class="subtitulo"><?php echo CFS()->get('subtitulo_slide_cestas'); ?></p>
    </div>
    <section class="splide" id="product">
      <div class="splide__track">
        <div class="splide__list">
          <?php handel_product_list_slide($data['slide']); ?>
        </div>
      </div>
    </section>
  </div>
</section>

<section class="logistica">
  <div class="container">
    <h2><?php echo CFS()->get('titulo_logistica'); ?></h2>
    <div class="grid grid-2-md gap-32">
      <?php $fields = CFS()->get('logistica'); ?>
      <?php if ($fields) { ?>
      <?php foreach ($fields as $field) { ?>
      <div class="item">
        <div>
          <img src="<?php echo esc_url($field['imagem']); ?>" alt="" srcset="">
        </div>
        <div>
          <h3><?php echo esc_html($field['titulo']); ?></h3>
          <p><?php echo esc_html($field['paragrafo']); ?></p>
        </div>
      </div>
      <?php } ?>
      <?php } ?>
    </div>
  </div>
</section>
<?php endwhile;
else: endif; ?>

<?php get_footer(); ?>