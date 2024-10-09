<?php
// Template Name: sac
get_header();

?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="sac">
      <div class="container">
        <section class="sac-via-apia">
          <div class="grid grid-2-md gap-32">
            <div>
              <h1>Conheça nossas Cestas</h1>
              <p>Uma seleção especial de produtos para todos os momentos.</p>
            </div>
            <form action="" method="get">
              <div class="grid grid-2 gap-10">
                <label for="nome">
                  <input type="text" id="nome" placeholder="Nome">
                </label>
                <label for="email">
                  <input type="email" id="email" placeholder="E-mail">
                </label>
              </div>
              <label for="Telefone/Whatsapp">
                <input type="text" placeholder="Telefone/Whatsapp" id="Telefone/Whatsapp">
              </label>
              <label for="Mensagem">
                <textarea placeholder="Mensagem"></textarea>
              </label>
              <div class="area-btn">
                <button>Enviar</button>
              </div>

            </form>
          </div>
        </section>
      </div>
    </main>
<?php endwhile;
else: endif; ?>

<?php get_footer(); ?>