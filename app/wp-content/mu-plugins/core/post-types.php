<?php
add_action('init', function() {
    // Déclarer les projets
    register_post_type('projet', [
        'labels' => [
            'name' => 'Projets',
            'singular_name' => 'Projet',
            'add_new' => 'Ajouter un projet',
            'add_new_item' => 'Ajouter un nouveau projet',
            'edit_item' => 'Modifier le projet',
            'new_item' => 'Nouveau projet',
            'view_item' => 'Voir le projet',
            'search_items' => 'Rechercher des projets',
            'not_found' => 'Aucun projet rencontré',
            'not_found_in_trash' => 'Aucun projet rencontré dans la corbeille',
        ],
        'public' => true,
        'menu_position' => 10,
        'menu_icon' => 'dashicons-portfolio',
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
    // Déclarer les réglages de THREE.JS
    register_post_type('reglages_3d', [
        'labels' => [
            'name' => 'Reglages 3D',],
        'public' => true,
        'menu_position' => 11,
        'menu_icon' => 'dashicons-art',
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
});