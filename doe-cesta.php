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

  <section class="doar-via-apia" v-else>
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
                <the-mask :mask="[' ###.###.###-##', '##.###.###/####-##' ]" type="text" v-model="form.cpfCnpj"
                  placeholder="CPF ou CNPJ*" required @input="limparEspacos('cpfCnpj')" @blur="validarCpfCnpj">
                </the-mask>
                <span v-if="erros.cpfCnpj" class="error">{{ erros.cpfCnpj }}</span>
              </label>
            </div>
            <div class="grid grid-2-md gap-10 form-input">
              <label>
                <the-mask mask="(##) #####-####" type="text" v-model="form.whatsapp" placeholder="WhatsApp*" required
                  @input="limparEspacos('whatsapp')" @blur="validarWhatsapp"></the-mask>
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
                <input type="text" :value="valor_cesta" disabled placeholder="Valor Unitário da Cesta">
              </label>
              <label>
                <input type="text" :value="total" disabled placeholder="Total">
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
            </div>
          </div>
          <div class="area-btn">
            <div class="item-center">
              <button type="submit">
                <span v-if="loading">
                  Carregando...
                </span>
                <span v-else>
                  Enviar
                </span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
  <div v-if="modalMessage" class="modal">
    <div class="modal-content">
      <div v-html="modalMessage"></div>
      <div class="modal-footer">
        <button @click="closeModal">Fechar</button>
      </div>
    </div>
  </div>
  <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/lib/vue-the-mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>

<script>
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
    cidade: '<?php echo esc_html(CFS()->get('cidade') ? esc_html(CFS()->get('cidade')) : 'SAO PAULO'); ?>',
    itens: [],
    imagem_cesta: '',
    titulo_cesta: '',
    valor_cesta: '',
    loading: false,
    modalMessage: '',
    cesta: {},
    code: '',
    copied: false,
    form: {
      nome: '',
      cpfCnpj: '',
      whatsapp: '',
      email: '',
      quantidade: 1,
      conheceu: ''
    },
    erros: {
      nome: '',
      cpfCnpj: '',
      whatsapp: '',
      email: '',
      conheceu: ''
    }
  },
  computed: {
    total() {
      const valorUnitario = this.transformarParaNumero(this.valor_cesta);
      return "R$ " + (valorUnitario * this.form.quantidade).toFixed(2).replace('.', ',');
    },
    valorNumerico() {
      const valorUnitario = this.transformarParaNumero(this.valor_cesta);
      return (valorUnitario * this.form.quantidade).toFixed(2);
    }
  },
  methods: {
    crc16(payload) {
      let crc = 0xFFFF;
      for (let i = 0; i < payload.length; i++) {
        crc ^= payload.charCodeAt(i) << 8;
        for (let j = 0; j < 8; j++) {
          crc = (crc & 0x8000) ? (crc << 1) ^ 0x1021 : crc << 1;
        }
      }
      return (crc & 0xFFFF).toString(16).toUpperCase().padStart(4, '0');
    },
    closeModal() {
      this.modalMessage = '';
      this.itens = [];
    },
    mostrarItens(doacao) {
      this.cesta = doacao;
      this.itens = doacao.item || [];
      this.imagem_cesta = doacao.imagem_cesta;
      this.titulo_cesta = doacao.titulo_cesta;
      this.valor_cesta = doacao.valor_cesta;
    },
    transformarParaNumero(valor) {
      if (!valor) return 0;
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
    validarConheceu() {
      this.erros.conheceu = !this.form.conheceu ? 'Escolha uma opção.' : '';
    },
    gerarQRCode(chavePix, responsavel, valor) {
      try {
        const qr = new QRious({
          size: 250,
          value: this.gerarPayloadPIX(chavePix, responsavel, valor)
        });
        return qr.toDataURL();
      } catch (e) {
        console.error("Erro ao gerar QR code:", e);
        return '';
      }
    },
    gerarPayloadPIX(chavePix, responsavel, valor) {
      if (!chavePix || typeof chavePix !== 'string') throw new Error('Chave PIX inválida.');
      if (!valor || isNaN(valor)) throw new Error('Valor PIX inválido.');

      valor = parseFloat(valor).toFixed(2); // Garantir formato correto

      const limparTexto = (texto, limite) => {
        return (texto || '')
          .normalize('NFD') // separa acentos
          .replace(/[\u0300-\u036f]/g, '') // remove acentos
          .replace(/[^A-Z0-9 ]/gi, '') // remove símbolos
          .substring(0, limite)
          .toUpperCase();
      };

      const nome = limparTexto(responsavel || this.nome || 'DOADOR', 25);
      const cidade = limparTexto(this.cidade || 'SAO PAULO', 15);

      function montaTLV(id, valor) {
        return id + valor.length.toString().padStart(2, '0') + valor;
      }

      const merchantAccountInfo = montaTLV('00', 'BR.GOV.BCB.PIX') + montaTLV('01', chavePix);
      const additionalData = montaTLV('05', '***');

      let payload =
        montaTLV('00', '01') +
        montaTLV('26', merchantAccountInfo) +
        montaTLV('52', '0000') +
        montaTLV('53', '986') +
        montaTLV('54', valor) +
        montaTLV('58', 'BR') +
        montaTLV('59', nome) +
        montaTLV('60', cidade) +
        montaTLV('62', additionalData);

      payload += '6304';
      payload += this.crc16(payload);

      this.code = payload; // Armazenar o código gerado

      return payload;
    },
    copiarChavePix() {
      const chavePix = this.code
      navigator.clipboard.writeText(chavePix)
        .then(() => {
          this.copied = true;
          // Atualiza o texto do botão
          const copyBtn = document.querySelector('.copy-btn');
          if (copyBtn) {
            copyBtn.textContent = 'Copiado!';
          }
          setTimeout(() => {
            this.copied = false;
            if (copyBtn) {
              copyBtn.textContent = 'Copiar';
            }
          }, 2000);
        })
        .catch(err => {
          console.error('Erro ao copiar texto: ', err);
          // Fallback para navegadores mais antigos
          const textarea = document.createElement('textarea');
          textarea.value = chavePix;
          document.body.appendChild(textarea);
          textarea.select();
          document.execCommand('copy');
          document.body.removeChild(textarea);
          this.copied = true;
          const copyBtn = document.querySelector('.copy-btn');
          if (copyBtn) {
            copyBtn.textContent = 'Copiado!';
          }
          setTimeout(() => {
            this.copied = false;
            if (copyBtn) {
              copyBtn.textContent = 'Copiar';
            }
          }, 2000);
        });
    },
    async enviarFormulario() {
      this.erros = {
        nome: '',
        cpfCnpj: '',
        whatsapp: '',
        email: '',
        conheceu: ''
      };

      if (!this.form.nome) this.erros.nome = 'Nome/Empresa é obrigatório.';
      if (!this.form.cpfCnpj) this.erros.cpfCnpj = 'CPF/CNPJ é obrigatório.';
      if (!this.form.whatsapp) this.erros.whatsapp = 'WhatsApp é obrigatório.';
      if (!this.form.email) this.erros.email = 'E-mail é obrigatório.';
      if (!this.form.conheceu) this.erros.conheceu = 'Informe onde conheceu a ONG.';

      if (Object.values(this.erros).every(e => !e)) {
        this.loading = true;
        try {
          const response = await fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
              action: 'enviar_doacao_cesta',
              nonce: '<?php echo wp_create_nonce("doacao_cesta_nonce"); ?>',
              nome: this.form.nome,
              cpfCnpj: this.form.cpfCnpj,
              whatsapp: this.form.whatsapp,
              email: this.form.email,
              quantidade: this.form.quantidade,
              conheceu: this.form.conheceu,
              titulo_cesta: this.titulo_cesta,
              valor_cesta: this.valor_cesta,
              total: this.total
            })
          });

          const data = await response.json();

          if (data.success) {
            const chavePix = this.cesta.chave_pix || this.chave_pix;
            const responsavel = this.cesta.responsavel || this.responsavel;
            const qrCodeImage = this.gerarQRCode(chavePix, responsavel, this.valorNumerico);

            this.modalMessage = `
              <div class="area-pix">
                <h1>Faça uma doação via PIX!</h1>
                <p>Ajude a manter nosso projeto e contribua com qualquer valor de forma rápida e segura.</p>
                
                <div class="steps">
                  <p><strong>1.</strong> Abra o app do seu banco e selecione a opção PIX.</p>
                  <p><strong>2.</strong> Escaneie o QR Code abaixo ou copie o código PIX.</p>
                  <p><strong>3.</strong> Confirme a transação e pronto!</p>
                </div>

                <div class="qrcode">
                  <img src="${qrCodeImage}" alt="QR Code PIX">
                </div>

                <p><strong>Responsável:</strong> ${responsavel}</p>
                <p><strong>Valor:</strong> ${this.total}</p>
                <p>Ou copie a chave PIX:</p>
                <div class="pix-code">
                  <button class="copy-btn" data-chave="${chavePix}">
                    ${this.copied ? 'Copiado!' : 'Copiar'}
                  </button>
                </div>
                <p class="thank-you">Muito obrigado pelo seu apoio! 💜</p>
                <p>Cada contribuição faz toda a diferença para nós.</p>
              </div>
            `;
            this.$nextTick(() => {
              const copyBtn = document.querySelector('.copy-btn');
              if (copyBtn) {
                copyBtn.addEventListener('click', () => {
                  this.copiarChavePix();
                });
              }
            });
          } else {
            this.modalMessage = data.message ||
              'Houve um problema ao enviar sua mensagem. Tente novamente mais tarde.';
          }
        } catch (error) {
          console.error("Erro ao enviar formulário:", error);
          this.modalMessage = 'Erro ao enviar formulário. Por favor, tente novamente.';
        } finally {
          this.loading = false;
        }
      }
    }
  }
});
</script>