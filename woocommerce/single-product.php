<?php get_header(); ?>
<?php
// Função para formatar os dados do produto
function format_single_product($product_id)
{
  $product = wc_get_product($product_id);

  if (!$product) {
    return false;
  }

  return [
    'id' => $product->get_id(),
    'name' => $product->get_name(),
    'sku' => $product->get_sku(),
    'price' => $product->get_price_html(),
    'gallery' => wp_get_attachment_image_src($product->get_image_id(), 'full'),
    'categories' => wp_get_post_terms($product_id, 'product_cat', ['fields' => 'names']),
  ];
}

// Verificação para evitar redeclaração da função format_products
if (!function_exists('format_products')) {
  function format_products($products)
  {
    $formatted_products = [];
    foreach ($products as $product) {
      if ($product) {
        $formatted_products[] = [
          'id' => $product->get_id(),
          'name' => $product->get_name(),
          'price' => $product->get_price_html(),
          'image' => wp_get_attachment_image_src($product->get_image_id(), 'medium')[0],
        ];
      }
    }
    return $formatted_products;
  }
}

$product_id = get_the_ID();
$product = wc_get_product($product_id);
$categories = $product->get_category_ids();
$category_image_url = '';

if (!empty($categories)) {
  $category = get_term($categories[0], 'product_cat');
  $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
  $category_image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
}
?>

<section id="app">
  <section class="bg-categoria" style="background: url(<?php echo esc_url($category_image_url); ?>);">
    <div class="container">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
          <?php
          $produto = format_single_product(get_the_ID());
          if ($produto):
          ?>
            <main class="produto">
              <div class="product-gallery" data-gallery="gallery">
                <div class="product-gallery-list">
                  <?php if (!empty($produto['gallery'])) { ?>
                    <img data-gallery="list" src="<?= esc_url($produto['gallery'][0]); ?>" alt="<?= esc_attr($produto['name']); ?>">
                  <?php } ?>
                </div>
              </div>

              <div class="product-detail">
                <small><?= esc_html($produto['sku']); ?></small>
                <h1><?= esc_html($produto['name']); ?></h1>
                <p class="product-categories"><?= implode(', ', $produto['categories']); ?></p>
                <p class="product-price"><?= $produto['price']; ?></p>
                <?php $fields = CFS()->get('comentario'); ?>
                <?php if ($fields) { ?>
                  <div class="product-comments">
                    <h3>Comentários</h3>
                    <section class="splide" id="comentarios">
                      <div class="splide__track">
                        <ul class="splide__list">
                          <?php foreach ($fields as $field) { ?>
                            <li class="splide__slide">
                              <div class="comments">
                                <p><?php echo esc_html($field['comentario_feito']); ?></p>
                                <h4>
                                  <i class="bi bi-chat-right-fill"></i>
                                  <span><?php echo esc_html($field['nome']); ?></span>
                                </h4>
                              </div>
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
                    </section>
                  </div>
                <?php } ?>
                <div class="cta">
                  <button @click.prevent="openModal" class="cta-comprar">Comprar</button>
                </div>
              </div>
            </main>
            <hr style="border: 1px solid <?= in_array('cesta-de-natal', $produto['categories']) ? '#8C0000' : '#ccc'; ?>;">
            <section class="descricao">
              <div class="grid">
                <?php $fields = CFS()->get('item'); ?>
                <ul class="item">
                  <?php if ($fields) { ?>
                    <?php foreach ($fields as $field) { ?>
                      <li><?php echo esc_html($field['item_da_cesta']); ?></li>
                    <?php } ?>
                  <?php } ?>
                </ul>
                <p><?php echo CFS()->get('informacao'); ?></p>
              </div>
            </section>
            <?php
            if (isset($produto)) {
              $related_ids = wc_get_related_products($produto['id'], 6);
              $related_products = [];
              foreach ($related_ids as $product_id) {
                $related_products[] = wc_get_product($product_id);
              }
              $related = format_products($related_products);
            }
            ?>
            <section class="relacinados">
              <div class="container">
                <h2 class="subtitulo">Relacionados</h2>
                <div class="grid grid-3-lg gap-32">
                  <?php handel_product_list($related); ?>
                </div>
              </div>
            </section>
          <?php else: ?>
            <p>Produto não encontrado.</p>
          <?php endif; ?>
        <?php endwhile; ?>
      <?php endif; ?>

      <div v-if="isModalOpen">
        <div class="modal">
          <div class="modal-content">
            <h2>Entre em contato para um orçamento</h2>
            <form @submit.prevent="comprar">
              <div class="grid grid-2 gap-10">
                <label for="nome" >
                  <input type="text" placeholder="Nome" id="nome" v-model="form.nome">
                  <span v-if="errors.nome" class="error">{{ errors.nome }}</span>
                </label>
                <label for="telefone_whatsapp">
                  <the-mask
                    mask="(##) #####-####"
                    v-model="form.telefone"
                    id="telefone_whatsapp"
                    placeholder="Telefone/Whatsapp">
                  </the-mask>
                  <span v-if="errors.telefone" class="error">{{ errors.telefone }}</span>
                </label>
              </div>
              <div>
                <label for="email">
                  <input type="email" id="email" placeholder="E-mail" v-model="form.email">
                  <span v-if="errors.email" class="error">{{ errors.email }}</span>
                </label>
              </div>
              <div class="grid grid-2 gap-10">
                <label for="cpfCnpj">
                  <the-mask
                    v-model="form.cpfCnpj"
                    :mask="['###.###.###-##', '##.###.###/####-##']"
                    placeholder="CPF ou CNPJ">
                  </the-mask>
                  <span v-if="errors.cpfCnpj" class="error">{{ errors.cpfCnpj }}</span>
                </label>
                <label for="quantidade">
                  <input type="number" placeholder="Quantidade" id="quantidade" v-model="form.quantidade" min="10">
                  <i>Quantidade mínima a partir de 10 cestas.</i>
                  <span v-if="errors.quantidade" class="error">{{ errors.quantidade }}</span>
                </label>
              </div>
              <div class="area-btn">
                <button type="submit" class="cta-enviar" :disabled="isLoading">
                  <span v-if="isLoading">Carregando...</span>
                  <span v-else>Enviar</span>
                </button>
                <button type="button" @click="closeModal">Fechar</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal de Mensagem de Sucesso ou Erro -->
      <div v-if="modalMessage" class="modal">
        <div class="modal-content">
          <p>{{ modalMessage }}</p>
          <div class="item-center">
            <button @click="closeModal" aria-label="Fechar">Fechar</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</section>

<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/lib/vue-the-mask.min.js"></script>

<script>
  new Vue({
    el: "#app",
    data() {
      return {
        isModalOpen: false,
        isLoading: false,
        modalMessage: null,
        form: {
          nome: '',
          telefone: '',
          email: '',
          cpfCnpj: '',
          quantidade: null,
          name: <?php echo json_encode($produto['name']); ?>,
          price: null
        },
        errors: {}
      };
    },
    mounted() {
      const priceElement = document.querySelector('.product-price');
      if (priceElement) {
        const priceText = priceElement.textContent.trim();
        const numericPrice = parseFloat(priceText.replace(/[^\d,]/g, '').replace(',', '.'));
        this.form.price = numericPrice;
      }
    },
    methods: {
      validateForm() {
        this.errors = {};
        if (!this.form.nome) this.errors.nome = "Nome é obrigatório.";
        if (!this.form.telefone) this.errors.telefone = "Telefone é obrigatório.";
        if (!this.form.email) this.errors.email = "Email é obrigatório.";
        if (!this.form.cpfCnpj) this.errors.cpfCnpj = "CPF ou CNPJ é obrigatório.";
        if (!this.form.quantidade || this.form.quantidade < 10) this.errors.quantidade = "Quantidade mínima é 10.";
        return Object.keys(this.errors).length === 0;
      },
      closeModal() {
        this.modalMessage = null;
        this.isModalOpen = false;
      },
      openModal() {
        this.isModalOpen = true;
      },
      async comprar() {
        if(!this.validateForm()) return
        this.isLoading = true;
        const data = {
          deal: {
            name: this.form.name
          },
          contacts: [{
            emails: [{
              email: this.form.email
            }],
            phones: [{
              phone: this.form.telefone,
              type: 'celular'
            }],
            title: this.form.cpfCnpj
          }],
          deal_products: [{
            name: this.form.name,
            price: Number(this.form.price),
            total: Number(this.form.price) * Number(this.form.quantidade)
          }]
        }

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
          } else {
            const errorData = await response.json();
            this.modalMessage = errorData.message || "Erro ao enviar a mensagem.";
          }
        } catch (error) {
          this.modalMessage = "Erro ao enviar a mensagem.";
        } finally {
          this.isLoading = false;
        }
      }
    }
  });
</script>