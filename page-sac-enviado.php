<?php
// Template Name: Enviado
get_header();
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="sac">
      <div class="container">
        <section class="sac-via-apia" id="sac-form">
        <h1>Mensagem envida com sucesso! Logo entatremos em conatato.</h1>
        </section>

        <section class="sac-contato">
          <div class="grid grid-2-md gap-32">
            <?php $fields = CFS()->get('dados'); ?>
            <?php if ($fields) { ?>
              <?php foreach ($fields as $field) { ?>
                <div class="item">
                  <img src="<?php echo esc_url($field['imagem']); ?>">
                  <div>
                    <h3><?php echo esc_html($field['tiulo-dados']); ?></h3>
                    <div><?php echo wp_kses_post($field['texo-dados']); ?></div>
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
        </section>
      </div>
    </main>
  <?php endwhile;
else: ?>
  <p><?php esc_html_e('Desculpe, nenhuma informação foi encontrada.'); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
