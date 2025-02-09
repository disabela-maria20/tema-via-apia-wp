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
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <hr class="divisor" />
    </div>

    <div class="form-doar">
      <div class="container">
        <form @submit.prevent="enviarFormulario">
          <h2>Preencha seus dados:</h2>
          <div class="form-area">
            <div class="grid grid-2-md gap-10 form-input">
              <label>
                <input type="text" v-model="form.nome" placeholder="Nome/Empresa*" required
                  @input="limparEspacos('nome')">
                <span v-if="erros.nome" class="error">{{ erros.nome }}</span>
              </label>
              <label>
                <input type="text" v-model="form.cpfCnpj" placeholder="CPF ou CNPJ*" required
                  @input="limparEspacos('cpfCnpj')" @blur="validarCpfCnpj">
                <span v-if="erros.cpfCnpj" class="error">{{ erros.cpfCnpj }}</span>
              </label>
            </div>
            <div class="grid grid-2-md gap-10 form-input">
              <label>
                <input type="text" v-model="form.whatsapp" placeholder="WhatsApp*" required
                  @input="limparEspacos('whatsapp')" @blur="validarWhatsapp">
                <span v-if="erros.whatsapp" class="error">{{ erros.whatsapp }}</span>
              </label>
              <label>
                <input type="email" v-model="form.email" placeholder="E-mail*" required @input="limparEspacos('email')"
                  @blur="validarEmail">
                <span v-if="erros.email" class="error">{{ erros.email }}</span>
              </label>
            </div>
            <div class="grid grid-3-md gap-10 form-input">
              <label>
                <input type="number" v-model="form.quantidade" placeholder="Quantidade" min="1" @input="atualizarTotal"
                  required>
              </label>
              <label>
                <input type="text" v-model="valor_cesta" disabled placeholder="Valor Unitário da Cesta">
              </label>
              <label>
                <input type="text" v-model="total" disabled placeholder="Total">
              </label>
            </div>
            <div class="form-input">
              <label>
                <select name="conheceu" v-model="form.conheceu" required @blur="validarConheceu">
                  <option value="" disabled selected>Onde você conheceu a ONG?</option>
                  <option value="internet">Internet</option>
                  <option value="amigo">Amigo</option>
                  <option value="familia">Família</option>
                  <option value="evento">Evento</option>
                  <option value="rede_social">Rede Social</option>
                  <option value="televisao">Televisão</option>
                  <option value="panfleto">Panfleto</option>
                  <option value="outro">Outro</option>
                </select>
                <span v-if="erros.conheceu" class="error">{{ erros.conheceu }}</span>
              </label>
              <label>
                <select name="forma_pagamento" v-model="form.forma_pagamento" required @blur="validarFormaPagamento">
                  <option value="" disabled selected>Forma de pagamento</option>
                  <option value="cartao_credito">Cartão de Crédito</option>
                  <option value="cartao_debito">Cartão de Débito</option>
                  <option value="boleto">Boleto</option>
                  <option value="pix">PIX</option>
                </select>
                <span v-if="erros.forma_pagamento" class="error">{{ erros.forma_pagamento }}</span>
              </label>
            </div>
          </div>
          <div class="area-btn">
            <div class="item-center">
              <button type="submit">Enviar</button>
            </div>
          </div>
        </form>
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