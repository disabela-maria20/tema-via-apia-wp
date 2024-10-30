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
            <label>
              <input type="text" v-model="nomeEmpresa" placeholder="Nome/Empresa*" :class="{'erro': erros.nomeEmpresa}">
              <span v-if="erros.nomeEmpresa">{{ mensagensErro.nomeEmpresa }}</span>
            </label>
            <label>
              <input type="text" v-model="cpfCnpj" placeholder="CPF ou CNPJ*" :class="{'erro': erros.cpfCnpj}">
              <span v-if="erros.cpfCnpj">{{ mensagensErro.cpfCnpj }}</span>
            </label>
          </div>
          <div class="grid grid-3-md gap-10">
            <label>
              <input type="email" v-model="email" placeholder="E-mail*" :class="{'erro': erros.email}">
              <span v-if="erros.email">{{ mensagensErro.email }}</span>
            </label>
            <label>
              <input type="text" v-model="whatsapp" placeholder="WhatsApp*" :class="{'erro': erros.whatsapp}">
              <span v-if="erros.whatsapp">{{ mensagensErro.whatsapp }}</span>
            </label>
            <label>
              <input type="text" v-model="quantidade" placeholder="Quantidade*" :class="{'erro': erros.quantidade}">
              <span v-if="erros.quantidade">{{ mensagensErro.quantidade }}</span>
            </label>
          </div>
        </div>
        <button type="submit">Enviar</button>
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
        nomeEmpresa: '', cpfCnpj: '', email: '', whatsapp: '', quantidade: '',
        erros: {}, 
        mensagensErro: {
          nomeEmpresa: 'Por favor, insira o nome da empresa.',
          cpfCnpj: 'Por favor, insira o CPF ou CNPJ.',
          email: 'Por favor, insira um e-mail válido.',
          whatsapp: 'Por favor, insira o número do WhatsApp.',
          quantidade: 'Por favor, insira uma quantidade válida.'
        }
      },
      methods: {
        validarFormulario() {
          this.erros = {};
          if (!this.nomeEmpresa) this.erros.nomeEmpresa = true;
          if (!this.cpfCnpj) this.erros.cpfCnpj = true;
          if (!this.validarEmail(this.email)) this.erros.email = true;
          if (!this.whatsapp) this.erros.whatsapp = true;
          if (!this.quantidade || isNaN(this.quantidade)) this.erros.quantidade = true;

          if (!Object.keys(this.erros).length) alert('Formulário enviado com sucesso!');
        },
        validarEmail(email) {
          return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
      }
    });
  </script>

<?php endwhile; endif; ?>
<?php get_footer(); ?>
