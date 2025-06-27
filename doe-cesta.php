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

  <section class="doar-via-apia">
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
        <div class="area-pix">
          <h3>{{cesta.nome}}</h3>
          <p>Responsável: {{cesta.responsavel}}</p>
          <p>E-mail do vendedor: <a href="mailto:">{{cesta.e_mail_do_vendedor}}</a></p>
          <p>Chave pix: {{cesta.chave_pix}}</p>
          <img :src="cesta.qrcode" alt="">
        </div>
      </div>
    </div>
  </section>
  <div v-if="modalMessage" class="modal">
    <div class="modal-content">
      <p>{{ modalMessage }}</p>
      <button @click="closeModal">Fechar</button>
    </div>
  </div>
  <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/lib/vue-the-mask.min.js"></script>

<script>
const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
const nonce = '<?php echo wp_create_nonce('doacao_cesta_nonce'); ?>';

new Vue({
  el: '#app',
  data: {
    titulo: '<?php echo esc_html(CFS()->get('titulo')); ?>',
    texto: `<?php echo html_entity_decode(wp_kses_post(CFS()->get('texto'))); ?>`,
    banner: '<?php echo esc_url(CFS()->get('banner')); ?>',
    doacoes: <?php echo json_encode(CFS()->get('doacao')); ?>,
    responsavel: '<?php echo esc_html(CFS()->get('responsavel')); ?>',
    e_mail_do_vendedor: '<?php echo esc_html(CFS()->get('e_mail_do_vendedor')); ?>',
    chave_pix: '<?php echo esc_html(CFS()->get('chave_pix')); ?>',
    qrcode: '<?php echo esc_html(CFS()->get('qrcode')); ?>',
    nome: '<?php echo esc_html(CFS()->get('nome')); ?>',
    itens: [],
    imagem_cesta: '',
    titulo_cesta: '',
    valor_cesta: '',
    loading: false,
    modalMessage: '',
    cesta: {}

  },
  computed: {
    total() {
      const valorUnitario = this.transformarParaNumero(this.valor_cesta);
      return "R$ " + (valorUnitario * this.form.quantidade).toFixed(2).replace('.', ',');
    }
  },
  methods: {
    closeModal() {
      this.modalMessage = '';
    },
    mostrarItens(doacao) {

      this.cesta = doacao
      console.log(this.cesta);

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
  }
});
</script>