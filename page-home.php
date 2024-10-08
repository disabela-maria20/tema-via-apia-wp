<?php
// Template Name: home
get_header();
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
          <h2>Conheça nossas Cestas</h2>
          <p>Uma seleção especial de produtos para todos os momentos.</p>
        </div>
      </div>
      <section class="splide" id="product">
        <div class="splide__track">
          <ul class="splide__list">
            <?php
            $args = array(
              'post_type' => 'product',
              'posts_per_page' => -1,
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post();
                global $product;

                $imagem = wp_get_attachment_url($product->get_image_id());
                $link = get_permalink($product->get_id());

                if ($imagem) :
            ?>
                  <li class="splide__slide">
                    <a href="<?php echo esc_url($link); ?>">
                      <img src="<?php echo esc_url($imagem); ?>" alt="<?php the_title(); ?>">
                    </a>
                  </li>
            <?php
                endif;
              endwhile;
              wp_reset_postdata();
            else :
              echo '<p>Nenhum produto encontrado.</p>';
            endif;
            ?>
          </ul>
        </div>
      </section>
    </section>

<?php endwhile;
else: endif; ?>

<?php get_footer(); ?>