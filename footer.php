<footer class="rodape">
  <div class="container">
    <div class="grid">
      <div>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/via-apia-rodape.png" alt="logo" width="103" height="73">
        <ul class="redes">
          <li>
            <a href="">
              <i class="bi bi-facebook"></i>
            </a>
          </li>
          <li>
            <a href="">
              <i class="bi bi-instagram"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="contato">
        <address>
          Rua Miro Vetorazzo, 1661
          Demarchi São Bernardo do Campo - SP
          CEP: 09820-135
        </address>
        <a href="tel:+55 (11) 2251.6115">+55 (11) 2251.6115</a>
        <a href="mailto:contato@viaapiafoods.com.br">contato@viaapiafoods.com.br</a>
        <p>CNPJ: 00.288.948/0001-94</p>
      </div>
      <nav>
        <?php
        $args = array(
          'menu' => 'principal',
          'theme_location' => 'menu-principal',
          'container' => false
        );
        wp_nav_menu($args); ?>
      </nav>
      <form id="newsletter-form" @submit.prevent="submitNewsletter">
        <div class="newsletter">
          <h2>Seja o primeiro a receber novidades!</h2>
          <div class="area-input">
            <input
              type="email"
              v-model="email"
              placeholder="Insira seu e-mail"
              :class="{ 'input-error': emailError }"
              required>
            <button type="submit">Inscreva-se</button>
          </div>
          <p v-if="emailError" class="error-message">{{ emailError }}</p>
          <div v-if="modalMessage" class="modal">
            <div class="modal-content">
              <p>{{ modalMessage }}</p>
              <div class="item-center">
                <button @click="closeModal" aria-label="Fechar">
                  fechar
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</footer>

<script src="<?php echo get_template_directory_uri(); ?>/assets/js/lib/vue@2.js"></script>
<script>
  new Vue({
    el: '#newsletter-form',
    data() {
      return {
        email: '',
        emailError: '',
        modalMessage: ''
      };
    },
    methods: {
      validateEmail() {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!this.email) {
          this.emailError = 'O e-mail é obrigatório.';
          return false;
        } else if (!emailPattern.test(this.email)) {
          this.emailError = 'Por favor, insira um e-mail válido.';
          return false;
        } else {
          this.emailError = '';
          return true;
        }
      },
      async submitNewsletter() {
        if (!this.validateEmail()) return;
        try {
          const response = await fetch('/wp-json/api/v1/enviar-contato', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: this.email })
        });

          if (response.ok) {
            this.modalMessage = 'Obrigado por se inscrever na nossa newsletter!';
            this.email = '';
          } else {
            this.modalMessage = 'Houve um problema ao enviar sua inscrição. Tente novamente mais tarde.';
          }
        } catch (error) {
          console.error('Erro ao enviar a inscrição:', error);
          this.modalMessage = 'Erro de conexão. Tente novamente mais tarde.';
        }
      },
      closeModal() {
        this.modalMessage = '';
      }
    }
  });
</script>
<?php wp_footer(); ?>
</body>

</html>