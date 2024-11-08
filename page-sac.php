<?php
// Template Name: sac
get_header();
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="sac">
      <div class="container">
        <section class="sac-via-apia" id="sac-form">
          <div class="grid grid-2-md gap-32">
            <div>
              <h1><?php echo esc_html(CFS()->get('titulo')); ?></h1>
              <p><?php echo esc_html(CFS()->get('subtitulo')); ?></p>
            </div>
            <?php echo do_shortcode('[wpforms id="309"]'); ?>
            <div v-if="modalMessage" class="modal">
              <div class="modal-content">
                <p>{{ modalMessage }}</p>
                <button @click="closeModal">Fechar</button>
              </div>
            </div>
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
  <?php endwhile;
else: ?>
  <p><?php esc_html_e('Desculpe, nenhuma informação foi encontrada.'); ?></p>
<?php endif; ?>

<script src="<?php echo get_template_directory_uri(); ?>/assets/js/lib/vue@2.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/lib/vue-the-mask.min.js"></script>

<script>
  new Vue({
    el: '#sac-form',
    data() {
      return {
        modalMessage: ''
      };
    },
    methods: {
      closeModal() {
        this.modalMessage = '';
      }
    },
    mounted() {
      document.addEventListener('wpformsAjaxSubmitSuccess', (event) => {
        const formId = '309';
        if (event.detail.data.form_id === formId && event.detail.response.success) {
          this.modalMessage = 'Obrigada! Logo Entraremos em contato.';
        }
      });
    }
  });
</script>

<?php get_footer(); ?>
