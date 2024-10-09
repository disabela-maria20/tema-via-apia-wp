<?php
// Template Name: logistica
get_header();
?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="logistica-via-apia">
      <div class="container">
        <h1><?php echo CFS()->get('logistica-titulo'); ?></h1>

        <section class="area-logistica">
          <ul>
            <?php $fields = CFS()->get('logistica'); ?>
            <?php if ($fields) { ?>
              <?php foreach ($fields as $field) { ?>
                <li class="grid">
                  <img src="<?php echo esc_html($field['imagem-logistica']); ?>" alt="">
                  <div>
                    <h3><?php echo esc_html($field['titulo-logistica-card']); ?></h3>
                    <p><?php echo esc_html($field['sub_titulo-logistica']); ?></p>
                  </div>
                </li>
              <?php } ?>
            <?php } ?>
          </ul>
        </section>

      </div>
    </main>
<?php endwhile;
else: endif; ?>

<?php get_footer(); ?>