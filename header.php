<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <title><?php the_title(); ?> | <?php bloginfo('name'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
</head>

<body>
  <header>
    <div class="container">
      <div class="area_menu">
        <div class="area_logo">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/via-apia-logo.png" alt="logo" width="103" height="73">
          <div class="menu_burguer">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
        <div class="menu">
          <nav>
            <?php
            $args = array(
              'menu' => 'principal',
              'theme_location' => 'menu-principal',
              'container' => false
            );
            wp_nav_menu($args); ?>
          </nav>
        </div>
      </div>
    </div>
  </header>