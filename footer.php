<footer class="rodape">
  <div class="container">
    <div class="grid">
      <div>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/via-apia-rodape.png" alt="logo" width="103" height="73">
        <ul class="redes">
          <li>
            <a href="">
              <i class="bi bi-facebook"></i>
            </a>
          </li>
          <li>
            <a href="">
              <i class="bi bi-instagram"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="contato">
        <address>
          Rua Miro Vetorazzo, 1661
          Demarchi São Bernardo do Campo - SP
          CEP: 09820-135
        </address>
        <a href="tel:+55 (11) 2251.6115">+55 (11) 2251.6115</a>
        <a href="mailto:contato@viaapiafoods.com.br">contato@viaapiafoods.com.br</a>
        <p>CNPJ: 00.288.948/0001-94</p>
      </div>
      <nav>
        <?php
        $args = array(
          'menu' => 'principal',
          'theme_location' => 'menu-principal',
          'container' => false
        );
        wp_nav_menu($args); ?>
      </nav>
      <form action="" method="post">
        <div class="newsletter">
          <h2>
            Seja o primeiro a
            receber novidades!
          </h2>
          <div class="area-input">
            <input type="text" placeholder="Insira seu e-mail">
            <button type="submit">Inscreva-se</button>
          </div>
        </div>

      </form>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>