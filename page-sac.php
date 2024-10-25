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
            <form @submit.prevent="submitForm">
              <div class="grid grid-2 gap-10">
                <label for="nome">
                  <input type="text" id="nome" v-model="nome" placeholder="Nome" :class="{ 'input-error': errors.nome }" required>
                  <p v-if="errors.nome" class="error-message">{{ errors.nome }}</p>
                </label>
                <label for="email">
                  <input type="email" id="email" v-model="email" placeholder="E-mail" :class="{ 'input-error': errors.email }" required>
                  <p v-if="errors.email" class="error-message">{{ errors.email }}</p>
                </label>
              </div>
              <label for="telefone_whatsapp">
                <input type="text" id="telefone_whatsapp" v-model="telefone" placeholder="Telefone/Whatsapp">
              </label>
              <label for="mensagem">
                <textarea id="mensagem" v-model="mensagem" placeholder="Mensagem"></textarea>
              </label>
              <div class="area-btn">
                <button type="submit">Enviar</button>
              </div>
            </form>
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

        try {
          const response = await fetch('https://crm.rdstation.com/api/v1/contacts?token=66b0d0105e54ad0022d2ba27', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              contact: {
                emails: [{
                  email: this.email
                }],
                phones: [{
                  phone: this.telefone,
                  type: 'Telefone/Whatsapp'
                }],
                title: this.mensagem,
                name: this.nome
              }
            })
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