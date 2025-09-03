<footer class="rodape">
  <div class="container">
    <div class="grid">
      <div>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/via-apia-rodape.png" alt="logo" width="103"
          height="73">
        <ul class="redes">
          <li>
            <a href="https://m.facebook.com/cestasviaapiaoriginal/" target="_blank">
              <i class="bi bi-facebook"></i>
            </a>
          </li>
          <li>
            <a href="https://br.linkedin.com/company/via-apia?trk=public_profile_topcard-current-company"
              target="_blank">
              <i class="bi bi-linkedin"></i>
            </a>
          </li>
          <li>
            <a href="https://www.instagram.com/cestas.viaapia/" target="_blank">
              <i class="bi bi-instagram"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="contato">
        <address>
          Rua Miro Vetorazzo, 1661 Demarchi São Bernardo do Campo - SP CEP: 09820-135
        </address>
        <p>CNPJ: 00.288.948/0001-94</p>
        <a href="tel:+55 (11) 99463-8310">+55 (11) 99463-8310</a>
        <a href="tel:+55 (11) 2251.6115">+55 (11) 2251.6115</a>
        <a href="mailto:contato@viaapiafoods.com.br">contato@viaapiafoods.com.br</a>
      </div>
      <nav>
        <?php
        $args = array(
          'menu' => 'menu rodape',
          'theme_location' => 'menu-rodape',
          'container' => false
        );
        wp_nav_menu($args); ?>
      </nav>
      <form id="newsletter-form" @submit.prevent="submitNewsletter">
        <div class="newsletter">
          <h2>Seja o primeiro a receber novidades!</h2>
          <div class="area-input">
            <input type="email" v-model="email" placeholder="Insira seu e-mail" :class="{ 'input-error': emailError }"
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
<script type="text/javascript">
_linkedin_partner_id = "8692145";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script>
<script type="text/javascript">
(function(l) {
  if (!l) {
    window.lintrk = function(a, b) {
      window.lintrk.q.push([a, b])
    };
    window.lintrk.q = []
  }
  var s = document.getElementsByTagName("script")[0];
  var b = document.createElement("script");
  b.type = "text/javascript";
  b.async = true;
  b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
  s.parentNode.insertBefore(b, s);
})(window.lintrk);
</script> <noscript> <img height="1" width="1" style="display:none;" alt=""
    src="https://px.ads.linkedin.com/collect/?pid=8692145&fmt=gif" /> </noscript>

<script async src="https://www.googletagmanager.com/gtag/js?id=G-B8R4E3CQL3"></script>
<script>
window.dataLayer = window.dataLayer || [];

function gtag() {
  dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', 'G-B8R4E3CQL3');
</script>

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
          body: JSON.stringify({
            email: this.email
          })
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