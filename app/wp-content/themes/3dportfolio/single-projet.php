<?php get_header(); ?>

<main class="container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <span class="badge">Projet Réalisé</span>
        <h1><?php the_title(); ?></h1>
    <?php 
        $image = get_field('image_du_projet'); 
        if( !empty( $image ) ): ?>
    <img src="<?php echo esc_url($image['url']); ?>" 
         alt="<?php echo esc_attr($image['alt']); ?>" 
         class="project-img" />
        <?php endif; ?>
        
        <div class="meta">
            <span><i data-lucide="calendar"></i> <?php the_field('annee'); ?></span>
            <span><i data-lucide="tag"></i> Développement Web</span>
        </div>

        <div class="content">
            <?php the_content();?>
        </div>

        <?php if (get_field('lien_du_projet')) : ?>
            <a href="<?php the_field('lien_du_projet'); ?>" target="_blank" class="btn-visit">
                Visiter le projet live <i data-lucide="external-link"></i>
            </a>
        <?php endif; ?>

    <?php endwhile; endif; ?>
</main>

<script>lucide.createIcons();</script>

<?php get_footer(); ?>