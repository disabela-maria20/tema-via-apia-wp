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
        <form @submit.prevent="POSTEntrarEmContato">
          <div class="lista">
            <label class="list" v-for="(item, index) in itens" :key="index">
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
                <the-mask
                  v-model="cpfCnpj"
                  :mask="['###.###.###-##', '##.###.###/####-##']"
                  placeholder="CPF ou CNPJ"
                  :class="{'erro': erros.cpfCnpj}">
                </the-mask>
                <span v-if="erros.cpfCnpj">{{ mensagensErro.cpfCnpj }}</span>
              </label>
            </div>
            <div class="grid grid-3-md gap-10">
              <label>
                <input type="email" v-model="email" placeholder="E-mail*" :class="{'erro': erros.email}">
                <span v-if="erros.email">{{ mensagensErro.email }}</span>
              </label>
              <label>
                <the-mask
                  mask="(##) #####-####"
                  v-model="whatsapp"
                  id="telefone_whatsapp"
                  placeholder="Telefone/Whatsapp"
                  :class="{'erro': erros.whatsapp}">
                </the-mask>
                <span v-if="erros.whatsapp">{{ mensagensErro.whatsapp }}</span>
              </label>
              <label>
                <input type="number" v-model="quantidade" placeholder="Quantidade*" :class="{'erro': erros.quantidade}">
                <span v-if="erros.quantidade">{{ mensagensErro.quantidade }}</span>
              </label>
            </div>
          </div>
          <div class="item-center">
            <button type="submit" :disabled="isLoading">
              <span v-if="!isLoading">Enviar</span>
              <span v-else>Enviando...</span>
            </button>
          </div>
        </form>
      </div>
      <div v-if="modalMessage" class="modal">
        <div class="modal-content">
          <p>{{ modalMessage }}</p>
          <div class="item-center">
            <button @click="closeModal" aria-label="Fechar">Fechar</button>
          </div>
        </div>
      </div>
    </main>

<?php endwhile;
endif; ?>
<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/lib/vue-the-mask.min.js"></script>

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
      erros: {},
      isLoading: false,
      modalMessage: '',
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
        if (!this.quantidade || isNaN(this.limparFormatoPreco(this.quantidade))) this.erros.quantidade = true;

        return !Object.keys(this.erros).length;
      },
      validarEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      },
      limparFormatoPreco(preco) {
        return parseFloat(preco.replace(/[^\d,]/g, '').replace(',', '.'));
      },
      async POSTEntrarEmContato() {
        if (!this.validarFormulario()) return;
        this.isLoading = true;

        const precoLimpo = this.limparFormatoPreco(this.quantidade);

        const data = {
          deal: {
            name: this.nomeEmpresa
          },
          contacts: [{
            emails: [{
              email: this.email
            }],
            phones: [{
              phone: this.whatsapp,
              type: 'celular'
            }],
            title: this.cpfCnpj
          }],
          deal_products: [{
            name: this.nomeEmpresa,
            price: precoLimpo,
            total: precoLimpo,
            description: this.itensSelecionados.join(', ')
          }]
        };

        try {
          const response = await fetch('/wp-json/v1/produto', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(data),
          });

          if (response.ok) {
            this.modalMessage = "Mensagem enviada com sucesso!";
            this.limparFormulario(); // Chama a função para limpar o formulário
          } else {
            const errorData = await response.json();
            this.modalMessage = errorData.message || "Erro ao enviar a mensagem.";
          }
        } catch (error) {
          this.modalMessage = "Erro ao enviar a mensagem.";
        } finally {
          this.isLoading = false;
        }
      },
      limparFormulario() {
        this.nomeEmpresa = '';
        this.cpfCnpj = '';
        this.email = '';
        this.whatsapp = '';
        this.quantidade = '';
        this.itensSelecionados = [];
        this.erros = {};
      },
      closeModal() {
        this.modalMessage = '';
      }
    }
  });
</script>