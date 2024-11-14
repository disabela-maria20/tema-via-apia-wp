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
          <p><?php echo wp_kses_post(CFS()->get('subtitulo')); ?></p>
        </div>
        <?php echo do_shortcode('[wpforms id="309"]');?>
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
      nome: '',
      email: '',
      telefone: '',
      mensagem: '',
      errors: {},
      modalMessage: ''
    };
  },
  methods: {
    cleanPhoneNumber(phone) {
      return phone.replace(/\D/g, '');
    },
    validateForm() {
      this.errors = {};
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!this.nome) {
        this.errors.nome = 'O nome é obrigatório.';
      }
      if (!this.email) {
        this.errors.email = 'O e-mail é obrigatório.';
      } else if (!emailPattern.test(this.email)) {
        this.errors.email = 'Por favor, insira um e-mail válido.';
      }

      return Object.keys(this.errors).length === 0;
    },
    async submitForm() {
      if (!this.validateForm()) return;

      const payload = {
        email: this.email,
        name: this.nome,
        phone: this.cleanPhoneNumber(this.telefone),
        title: this.mensagem
      }
      try {
        const response = await fetch('/wp-json/sac/v1/subscribe', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        if (response.ok) {
          this.modalMessage = 'Sua mensagem foi enviada com sucesso!';
          this.resetForm();
        } else {
          this.modalMessage = 'Houve um problema ao enviar sua mensagem. Tente novamente mais tarde.';
        }
      } catch (error) {
        console.error('Erro ao enviar a mensagem:', error);
        this.modalMessage = 'Erro de conexão. Tente novamente mais tarde.';
      }
    },
    closeModal() {
      this.modalMessage = '';
    },
    resetForm() {
      this.nome = '';
      this.email = '';
      this.telefone = '';
      this.mensagem = '';
      this.errors = {};
    }
  }
});
</script>

<?php get_footer(); ?>