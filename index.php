<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<main class="container padrao">
			<h1 class="titulo"><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</main>
	<?php endwhile;
else: ?>
	</article>
	<section class="introducao-interna introducao-geral">
		<div class="container">
			<h1>Algo deu errado!</h1>
		</div>
	</section>
<?php endif; ?>
<?php get_footer(); ?>