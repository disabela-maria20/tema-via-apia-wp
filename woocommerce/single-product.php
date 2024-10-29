<?php get_header(); ?>

<?php
function format_single_product($id, $img_size = 'medium')
{
  $product = wc_get_product($id);

  if (!$product) {
    return null;
  }

  $gallery_ids = $product->get_gallery_attachment_ids();
  $gallery = [];
  if ($gallery_ids) {
    foreach ($gallery_ids as $img_id) {
      $gallery[] = wp_get_attachment_image_src($img_id, $img_size)[0];
    }
  }
  $categories = wc_get_product_category_list($id);

  return [
    'id' => $id,
    'name' => $product->get_name(),
    'price' => $product->get_price_html(),
    'link' => $product->get_permalink(),
    'sku' => $product->get_sku(),
    'description' => $product->get_description(),
    'img' => wp_get_attachment_image_src($product->get_image_id(), $img_size)[0],
    'gallery' => $gallery,
    'categories' => $categories,
  ];
}
?>

<?php
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
                  <?php foreach ($produto['gallery'] as $img) { ?>
                    <img data-gallery="list" src="<?= esc_url($img); ?>" alt="<?= esc_attr($produto['name']); ?>">
                  <?php } ?>
                </div>
              </div>

              <div class="product-detail">
                <small><?= esc_html($produto['sku']); ?></small>
                <h1><?= esc_html($produto['name']); ?></h1>
                <p class="product-categories"><?= $produto['categories']; ?></p>
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
            <hr style="border: 1px solid <?= $produto['categories'] == 'cesta-de-natal' ? '#8C0000' : '#ccc'; ?>;">
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
                <label for="nome">
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
                <button type="submit" class="cta-enviar">Enviar</button>
                <button type="button" @click="closeModal">Fechar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</section>

<script src="<?php echo get_template_directory_uri(); ?>/assets/js/lib/vue@2.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/lib/vue-the-mask.min.js"></script>
<script>
  new Vue({
    el: "#app",
    data() {
      return {
        isModalOpen: false,
        form: {
          nome: '',
          telefone: '',
          email: '',
          cpfCnpj: '',
          quantidade: null,
          name: <?php echo json_encode($produto['name']); ?>,
          price: <?php echo json_encode($produto['price']); ?>,
        },
        errors: {}
      };
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
        this.isModalOpen = false;
      },
      openModal() {
        this.isModalOpen = true;
      },

      async comprar() {
        if (!this.validateForm()) return;

        try {
          const response = await fetch('/wp-json/produto-contato/v1/enviar-contato', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(this.form)
          });

          if (!response.ok) {
            throw new Error('Erro ao enviar os dados.');
          }
          this.closeModal();
        } catch (error) {
          alert(error.message);
        }
      },
    }
  });
</script>

<?php get_footer(); ?>
