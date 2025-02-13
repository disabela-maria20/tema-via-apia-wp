<?php
// Template Name: doe cesta
get_header();
?>

<div id="app">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
  <main class="doe-cesta-via-apia" v-if="itens.length === 0">
    <div class="container">
      <h1>{{ titulo }}</h1>
      <div class="descricao">
        <div v-html="texto"></div>
      </div>
    </div>

    <div class="container">
      <div class="card">
        <div v-for="(doacao, index) in doacoes" :key="index" class="card-item">
          <div class="img-associacao">
            <img :src="doacao.imagem" :alt="doacao.titulo">
          </div>
          <div class="associacao">
            <h2>{{ doacao.titulo }}</h2>
            <p v-html="doacao.texto"></p>
            <a href="javascript:void(0)" @click="mostrarItens(doacao)" class="doar-cesta-link">Doar Cesta</a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <section v-if="itens.length > 0" class="doar-via-apia">
    <div class="doar">
      <div class="container">
        <div class="banner">
          <img :src="banner" alt="Banner da página de doação">
        </div>
        <div class="grid">
          <div class="img-cesta">
            <img :src="imagem_cesta" :alt="titulo_cesta">
          </div>
          <div class="dados-cesta">
            <h3>{{ titulo_cesta }}</h3>
            <p v-html="valor_cesta"></p>
          </div>
          <div class="itens-cesta">
            <h2>Itens da Cesta</h2>
            <ul class="itens-da-cesta">
              <li v-for="(item, index) in itens" :key="index">{{ item.item_da_cesta }}</li>
            </ul>
            <div class="area-btn">
              <a class="btn" href="https://api.whatsapp.com/send?phone=5511994638310">Doar cesta</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>

<script>
new Vue({
  el: '#app',
  data: {
    titulo: '<?php echo esc_html(CFS()->get('titulo')); ?>',
    texto: `<?php echo html_entity_decode(wp_kses_post(CFS()->get('texto'))); ?>`,
    banner: '<?php echo esc_url(CFS()->get('banner')); ?>',
    doacoes: <?php echo json_encode(CFS()->get('doacao')); ?>,
    itens: [],
    imagem_cesta: '',
    titulo_cesta: '',
    valor_cesta: '',
    form: {
      nome: '',
      cpfCnpj: '',
      whatsapp: '',
      email: '',
      quantidade: 1,
      forma_pagamento: '',
      conheceu: ''
    },
    erros: {
      nome: '',
      cpfCnpj: '',
      whatsapp: '',
      email: '',
      forma_pagamento: '',
      conheceu: ''
    }
  },
  computed: {
    total() {
      const valorUnitario = this.transformarParaNumero(this.valor_cesta);
      return "R$ " + (valorUnitario * this.form.quantidade).toFixed(2).replace('.', ',');
    }
  },
  methods: {
    mostrarItens(doacao) {
      this.itens = doacao.item || [];
      this.imagem_cesta = doacao.imagem_cesta;
      this.titulo_cesta = doacao.titulo_cesta;
      this.valor_cesta = doacao.valor_cesta;
    },
    transformarParaNumero(valor) {
      const valorLimpo = valor.replace(/R\$|\s|,/g, '');
      return parseFloat(valorLimpo) / 100;
    },
    atualizarTotal() {
      this.$forceUpdate();
    },
    limparEspacos(campo) {
      this.form[campo] = this.form[campo].trim();
    },
    validarCpfCnpj() {
      const valor = this.form.cpfCnpj.replace(/\D/g, '');
      this.erros.cpfCnpj = valor.length < 11 ? 'CPF/CNPJ inválido.' : '';
    },
    validarWhatsapp() {
      const valor = this.form.whatsapp.replace(/\D/g, '');
      this.erros.whatsapp = valor.length < 11 ? 'Número de WhatsApp inválido.' : '';
    },
    validarEmail() {
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      this.erros.email = !regex.test(this.form.email) ? 'E-mail inválido.' : '';
    },
    validarFormaPagamento() {
      this.erros.forma_pagamento = !this.form.forma_pagamento ? 'Escolha uma forma de pagamento.' : '';
    },
    validarConheceu() {
      this.erros.conheceu = !this.form.conheceu ? 'Escolha uma opção.' : '';
    },
    enviarFormulario() {
      this.erros = {
        nome: '',
        cpfCnpj: '',
        whatsapp: '',
        email: '',
        forma_pagamento: '',
        conheceu: ''
      };

      if (!this.form.nome) this.erros.nome = 'Nome/Empresa é obrigatório.';
      if (!this.form.cpfCnpj) this.erros.cpfCnpj = 'CPF/CNPJ é obrigatório.';
      if (!this.form.whatsapp) this.erros.whatsapp = 'WhatsApp é obrigatório.';
      if (!this.form.email) this.erros.email = 'E-mail é obrigatório.';
      if (!this.form.conheceu) this.erros.conheceu = 'Informe onde conheceu a ONG.';
      if (!this.form.forma_pagamento) this.erros.forma_pagamento = 'Forma de pagamento é obrigatória.';

      if (Object.values(this.erros).every(e => !e)) {
        alert('Formulário enviado com sucesso!');
      }
    }
  }
});
</script>