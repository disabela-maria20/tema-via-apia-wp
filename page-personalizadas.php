<?php
// Template Name: Personalizadas
get_header();
?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
<main id="app" class="page-personalizadas">
  <div class="container">
    <div class="grid grid-2-md gap-32">
      <div>
        <img src="<?php echo CFS()->get('imagem'); ?>" alt="">
      </div>
      <div>
        <h1><?php echo esc_html(CFS()->get('titulo')); ?></h1>
        <div><?php echo wp_kses_post(CFS()->get('texto')); ?></div>

        <div class="area-btn">
          <a class="btn"
            href="https://api.whatsapp.com/send?phone=5511994638310&text=Olá, gostaria de saber mais sobre a cesta personalizada.">Comprar</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php endwhile;
endif; ?>
<?php get_footer(); ?>