<?php
// Template Name: doe cesta
get_header();
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="doe-cesta-via-apia">
      <div class="container">
        <h1><?php echo esc_html(CFS()->get('titulo')); ?></h1>
        <div>
          <?php echo wp_kses_post(CFS()->get('texto')); // Permite tags HTML seguras 
          ?>
        </div>
      </div>
      <div class="container">
        <div class="card">
          <?php $fields = CFS()->get('doacao'); ?>
          <?php if ($fields) { ?>
            <?php foreach ($fields as $field) { ?>
              <div class="card-item">
                <div class="img-associacao">
                  <img src="<?php echo esc_url($field['imagem']); ?>" alt="<?php echo esc_attr($field['titulo']); ?>">
                </div>
                <div class="associação">
                  <h3><?php echo esc_html($field['titulo']); ?></h3>
                  <p><?php echo esc_html($field['texto']); ?></p>
                </div>
                <div class="cestas">
                  <div>
                    <img src="<?php echo esc_url($field['imagem_cesta']); ?>" alt="<?php echo esc_attr($field['titulo_cesta']); ?>">
                  </div>
                  <div>
                    <h3><?php echo esc_html($field['titulo_cesta']); ?></h3>
                    <p><?php echo esc_html($field['valor-cesta']); ?></p>
                    <div  class="item-center">
                      <a href="#" class="doar-cesta-link">Doar Cesta</a>
                    </div>

                  </div>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </main>
<?php endwhile;
else: endif; ?>

<?php get_footer(); ?>