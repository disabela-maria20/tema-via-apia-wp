<?php
// Template Name: Personalizadas
get_header();
?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
  <main id="app" class="page-personalizadas">
    <div class="container">
      <div class="grid grid-2-md gap-32">
        <div>
          <img :src="imagem" alt="">
        </div>
        <div>
          <h1>{{ titulo }}</h1>
          <div v-html="texto"></div>
        </div>
      </div>
      <hr class="divisor" />
      <h2>{{ titulo_produtos }}</h2>
      <form @submit.prevent="validarFormulario">
        <div class="lista">
          <label v-for="(item, index) in itens" :key="index">
            <input type="checkbox" :value="item" v-model="itensSelecionados">
            <span>{{ item }}</span>
          </label>
        </div>
        <h2>Preencha seus dados:</h2>
        <div class="area-input">
          <div class="grid grid-2-md gap-10">
            <label for="NomeEmpresa">
              <input type="text" id="NomeEmpresa" v-model="nomeEmpresa" placeholder="Nome/Empresa*" :class="{'erro': erros.nomeEmpresa}">
            </label>
            <label for="CPFCNPJ">
              <input type="text" id="CPFCNPJ" v-model="cpfCnpj" placeholder="CPF ou CNPJ*" :class="{'erro': erros.cpfCnpj}">
            </label>
          </div>
          <div class="grid grid-3-md gap-10">
            <label for="Email">
              <input type="email" id="Email" v-model="email" placeholder="E-mail*" :class="{'erro': erros.email}">
            </label>
            <label for="WhatsApp">
              <input type="text" id="WhatsApp" v-model="whatsapp" placeholder="WhatsApp*" :class="{'erro': erros.whatsapp}">
            </label>
            <label for="Quantidade">
              <input type="text" id="Quantidade" v-model="quantidade" placeholder="Quantidade*" :class="{'erro': erros.quantidade}">
            </label>
          </div>
          <label for="Ofertas">
            <input type="checkbox" v-model="receberOfertas">
            <span>Desejo Receber Informativos e Ofertas</span>
          </label>
        </div>
        <div class="item-center">
          <div class="area-btn">
            <button type="submit">Enviar</button>
          </div>
        </div>
      </form>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
  <script>
    new Vue({
      el: '#app',
      data: {
        imagem: '<?php echo CFS()->get('imagem'); ?>',
        titulo: '<?php echo esc_html(CFS()->get('titulo')); ?>',
        texto: `<?php echo wp_kses_post(CFS()->get('texto')); ?>`,
        titulo_produtos: '<?php echo esc_html(CFS()->get('titulo_produtos')); ?>',
        itens: <?php echo json_encode(array_column(CFS()->get('itens'), 'item')); ?>,
        itensSelecionados: [],
        nomeEmpresa: '',
        cpfCnpj: '',
        email: '',
        whatsapp: '',
        quantidade: '',
        receberOfertas: false,
        erros: {
          nomeEmpresa: false,
          cpfCnpj: false,
          email: false,
          whatsapp: false,
          quantidade: false
        }
      },
      methods: {
        validarFormulario() {
          this.limparErros();
          let formularioValido = true;

          if (!this.nomeEmpresa) {
            this.erros.nomeEmpresa = true;
            formularioValido = false;
          }
          if (!this.cpfCnpj) {
            this.erros.cpfCnpj = true;
            formularioValido = false;
          }
          if (!this.email || !this.validarEmail(this.email)) {
            this.erros.email = true;
            formularioValido = false;
          }
          if (!this.whatsapp) {
            this.erros.whatsapp = true;
            formularioValido = false;
          }
          if (!this.quantidade || isNaN(this.quantidade)) {
            this.erros.quantidade = true;
            formularioValido = false;
          }

          if (formularioValido) {
            alert('Formulário enviado com sucesso!');
            // Aqui você pode enviar o formulário
          }
        },
        limparErros() {
          this.erros = {
            nomeEmpresa: false,
            cpfCnpj: false,
            email: false,
            whatsapp: false,
            quantidade: false
          };
        },
        validarEmail(email) {
          const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          return regex.test(email);
        }
      }
    });
  </script>

<?php endwhile; else: endif; ?>

<?php get_footer(); ?>
