<?php
/**
 * Config du thème
 */

// Projets
function wpm_custom_post_type() {
    $labels = array(
        'name' => 'Projets',
        'singular_name' => 'Projet',
        'add_new' => 'Ajouter un projet',
        'add_new_item' => 'Ajouter un nouveau projet',
        'edit_item' => 'Modifier le projet',
        'new_item' => 'Nouveau projet',
        'view_item' => 'Voir le projet',
        'search_items' => 'Rechercher des projets',
        'not_found' => 'Aucun projet trouvé',
        'not_found_in_trash' => 'Aucun projet trouvé dans la corbeille',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-portfolio',
        'menu_position' => 10,
    );

    register_post_type('projet', $args);
}

// Créer les taxonomies pour les projets
function wpm_custom_taxonomies() {
    $labels = array(
        'name' => 'Catégories de projets',
        'singular_name' => 'Catégorie de projet',
        'search_items' => 'Rechercher des catégories de projets',
        'all_items' => 'Toutes les catégories de projets',
        'edit_item' => 'Modifier la catégorie de projet',
        'update_item' => 'Mettre à jour la catégorie de projet',
        'add_new_item' => 'Ajouter une nouvelle catégorie de projet',
        'new_item_name' => 'Nom de la nouvelle catégorie de projet',
        'menu_name' => 'Catégories de projets',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'projet-categorie'),
    );

    register_taxonomy('projet-categorie', array('projet'), $args);
}

add_action( 'acf/include_fields', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
    'key' => 'group_69bd1e247b0a7',
    'title' => 'Détails du Projet',
    'fields' => array(
        array(
            'key' => 'field_69bd1e2a73df4',
            'label' => 'Image du projet (musée)',
            'name' => 'image_du_projet',
            'type' => 'image',
            'return_format' => 'url',
        ),
        array(
            'key' => 'field_69bd1e4a73df5',
            'label' => 'Lien du projet',
            'name' => 'lien_du_projet',
            'type' => 'text',
        ),
        array(
            'key' => 'field_69bd1e4f73df6',
            'label' => 'Année',
            'name' => 'annee',
            'type' => 'text',
        ),
        array(
            'key' => 'field_69bd1e5673df7',
            'label' => 'Description du projet',
            'name' => 'description_du_projet',
            'type' => 'textarea',
        ),
        array(
            'key' => 'field_69bd1e5c73df8',
            'label' => 'Vidéo',
            'name' => 'video',
            'type' => 'url',
        ),
        array(
            'key' => 'field_69bd1e6273df9',
            'label' => 'Image (iframe)',
            'name' => 'image',
            'type' => 'image',
            'return_format' => 'url',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'projet',
            ),
        ),
    ),
    'show_in_rest' => 1,
) );
} );

add_action('init', 'wpm_custom_post_type');
add_action('init', 'wpm_custom_taxonomies');

// Réglages 3D
function wpm_custom_post_type_3d() {
    $args = array(
        'labels' => array('name' => 'Reglages 3D'),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-art',
        'menu_position' => 11,
    );
    register_post_type('reglages_3d', $args);
}

function wpm_custom_taxonomies_3d() {
    register_taxonomy('3d-categorie', array('reglages_3d'), array(
        'labels' => array('name' => 'Catégories 3D'),
        'hierarchical' => true,
        'show_in_rest' => true,
    ));
}

add_action('init', 'wpm_custom_post_type_3d');
add_action('init', 'wpm_custom_taxonomies_3d');

/*
Charger le style
*/

function load_styles() {
    wp_enqueue_style('main-style', get_stylesheet_uri());

    wp_enqueue_style(
        'archive-style', 
        get_template_directory_uri() . '/assets/css/archive.css', 
        array(), 
        '1.0'
    );

    wp_enqueue_style(
        'projet-style', 
        get_template_directory_uri() . '/assets/css/single-projet.css', 
        array(), 
        '1.0'
    );

    wp_enqueue_style(
        'musee-style', 
        get_template_directory_uri() . '/assets/css/page-musee.css', 
        array(), 
        '1.0'
    );
}

add_action('wp_enqueue_scripts', 'load_styles');

function load_scripts() {
    wp_enqueue_script(
        'musee-js', 
        get_template_directory_uri() . '/assets/js/musee-immersif.js', 
        array(), 
        '1.0', 
        true // Pour charger dans le footer
    );
}

add_action('wp_enqueue_scripts', 'load_scripts');

add_filter('script_loader_tag', function($tag, $handle, $src) {
    if ('musee-js' !== $handle) {
        return $tag;
    }
    return '<script type="module" src="' . esc_url($src) . '"></script>';
}, 10, 3);