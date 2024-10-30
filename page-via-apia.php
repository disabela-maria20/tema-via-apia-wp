<?php
// Template Name: via apia
get_header();
?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="via-apia">
      <section>
        <div class="container">
          <div class="grid grid-2-md gap-32">
            <img src="<?php echo CFS()->get('imagem-via-apia'); ?>" alt="">
            <div>
              <h2><?php echo CFS()->get('titulo-via-apia'); ?></h2>
              <div>
                <?php echo CFS()->get('texto-via-apia'); ?>
              </div>
            </div>
          </div>
          <div class="item-center">
            <a href="<?php echo CFS()->get('cta_link'); ?>" download><?php echo CFS()->get('cta_baixar-via-apia'); ?></a>
          </div>
        </div>
      </section>

      <section class="vendas-via-apia">
        <div class="container">
          <h2><?php echo CFS()->get('titulo_vendas'); ?></h2>
          <div class="grid grid-2-md gap-32">
            <?php $fields = CFS()->get('contato-via-apia'); ?>
            <?php if ($fields) { ?>
              <?php foreach ($fields as $field) { ?>
                <div class="item">
                  <div>
                    <h3><?php echo esc_html($field['tiulo-contao-via-apia']); ?></h3>
                    <p><?php echo esc_html($field['texto-vendas-via-apia']); ?></p> 
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
          <div class="item-center">
            <a c><?php echo CFS()->get('cta-vendas-via-apia'); ?></a>
          </div>
        </div>
      </section>
    </main>
<?php endwhile;
else: endif; ?>

<?php get_footer(); ?>