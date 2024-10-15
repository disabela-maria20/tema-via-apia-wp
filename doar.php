<?php
// Template Name: doar
get_header();
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="doar-via-apia">
      
    </main>
<?php endwhile;
else: endif; ?>

<?php get_footer(); ?>