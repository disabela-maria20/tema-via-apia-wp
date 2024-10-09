<?php
// Template Name: sac
get_header();
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="sac">
      <div class="container">
        <section class="sac-via-apia">
          <div class="grid grid-2-md gap-32">
            <div>
              <h1><?php echo esc_html(CFS()->get('titulo')); ?></h1>
              <p><?php echo esc_html(CFS()->get('subtitulo')); ?></p>
            </div>
            <form action="" method="get">
              <div class="grid grid-2 gap-10">
                <label for="nome">
                  <input type="text" id="nome" name="nome" placeholder="Nome">
                </label>
                <label for="email">
                  <input type="email" id="email" name="email" placeholder="E-mail">
                </label>
              </div>
              <label for="telefone_whatsapp">
                <input type="text" id="telefone_whatsapp" name="telefone_whatsapp" placeholder="Telefone/Whatsapp">
              </label>
              <label for="mensagem">
                <textarea id="mensagem" name="mensagem" placeholder="Mensagem"></textarea>
              </label>
              <div class="area-btn">
                <button type="submit">Enviar</button>
              </div>
            </form>
          </div>
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
<?php endwhile; else: ?>
    <p><?php esc_html_e('Desculpe, nenhuma informação foi encontrada.'); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
