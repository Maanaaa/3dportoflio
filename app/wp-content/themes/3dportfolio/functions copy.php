<?php
/**
 * CONFIGURATION THÈME CUSTOM
 */

add_action('init', function() {
    // Cacher ACF
    add_filter('acf/settings/show_admin', '__return_false');

    // Nettoyage Head
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Désactiver Commentaires (Post types)
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');
});

// Faire croire que les extensions et thèmes sont à jour (Cleanup notifications)
add_filter('pre_site_transient_update_plugins', '__return_null');
add_filter('pre_site_transient_update_themes', '__return_null');

// Désactiver Commentaires (Global)
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);


/**
 * WHITE LABEL (INTERFACE & LOGOS)
 */

// PERSONNALISATION DU LOGIN (LOGO SVG)
add_action('login_enqueue_scripts', function() {
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_template_directory_uri(); ?>/icon.svg) !important;
            background-size: contain !important;
            width: 80px;
            height: 80px;
            transition: transform 0.3s ease;
        }
        #login h1 a:hover { transform: scale(1.05); }
    </style>
    <?php
});

add_filter('login_headerurl', function() { return home_url(); });
add_filter('login_headertext', function() { return get_bloginfo('name'); });

// LOGO DANS LE MENU LATÉRAL & DÉSACTION DU WELCOME PANEL
add_action('admin_head', function() {
    remove_action('welcome_panel', 'wp_welcome_panel');
    
    echo '<style>
        #adminmenu #menu-dashboard .wp-menu-image:before {
            display: none !important;
        }

        #adminmenu #menu-dashboard .wp-menu-image {
            background: url("' . get_template_directory_uri() . '/icon.svg") no-repeat !important;
            background-size: 20px 20px !important; /* Taille fixe pour éviter l écrasement */
            background-position: 8px center !important; /* Alignement horizontal et vertical */
        }

        #adminmenu .wp-menu-image:before {
            display: inline-block;
        }

        .update-nag, .updated, .core-updates { display: none !important; } 
    </style>';
    
    remove_action('admin_notices', 'update_nag', 3);
    remove_action('network_admin_notices', 'update_nag', 3);
}, 1);

// MODIFICATION DE LA BARRE D'ADMIN (TOP BAR)
add_action('admin_bar_menu', function($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('new-post');
    $wp_admin_bar->remove_menu('comments');

    $wp_admin_bar->add_node([
        'id'    => 'custom-logo',
        'title' => '<img src="' . get_template_directory_uri() . '/icon.svg" style="width:20px; height:20px; margin-top:5px;">',
        'href'  => home_url(),
        'meta'  => ['title' => 'Aller sur le site'],
    ]);
}, 999);


/**
 * GESTION DES MENUS (RELAX & HIDE)
 */

add_action('admin_menu', function () {
    remove_menu_page('edit.php'); // Articles
    remove_menu_page('edit.php?post_type=page'); // Pages
    remove_menu_page('upload.php'); // Médias
    remove_menu_page('edit-comments.php'); // Commentaires
    //remove_menu_page('plugins.php'); // Extensions
    remove_submenu_page('index.php', 'update-core.php'); 
}, 999);

add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
});


/**
 * Fonctionnalitées
 */

/* ACF */ 
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
            'key' => 'field_69bd1e4a73df5',
            'label' => 'Lien du projet',
            'name' => 'lien_du_projet',
            'aria-label' => '',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'maxlength' => '',
            'allow_in_bindings' => 0,
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
        ),
        array(
            'key' => 'field_69bd1e4f73df6',
            'label' => 'Année',
            'name' => 'annee',
            'aria-label' => '',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'maxlength' => '',
            'allow_in_bindings' => 0,
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
        ),
        array(
            'key' => 'field_69bd1e5673df7',
            'label' => 'Description du projet',
            'name' => 'description_du_projet',
            'aria-label' => '',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'maxlength' => '',
            'allow_in_bindings' => 0,
            'rows' => '',
            'placeholder' => '',
            'new_lines' => '',
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
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 1,
    'display_title' => '',
) );
} );

add_action('init', 'wpm_custom_post_type');
add_action('init', 'wpm_custom_taxonomies');

// Créer le custom post pour les réglages 3D 
function wpm_custom_post_type_3d() {
    $labels = array(
        'name' => 'Reglages 3D',
        'singular_name' => 'Reglages 3D',
        'menu_name' => 'Reglages 3D',
        'name_admin_bar' => 'Reglages 3D',
        'add_new' => 'Ajouter un reglages 3D',
        'add_new_item' => 'Ajouter un reglages 3D',
        'new_item' => 'Nouveau reglages 3D',
        'edit_item' => 'Modifier reglages 3D',
        'view_item' => 'Voir reglages 3D',
        'all_items' => 'Tous les reglages 3D',
        'search_items' => 'Rechercher des reglages 3D',
        'not_found' => 'Aucun reglages 3D trouvé',
        'not_found_in_trash' => 'Aucun reglages 3D dans la corbeille',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-art',
        'menu_position' => 11,
        
    );
    register_post_type('reglages_3d', $args);
}
add_action('init', 'wpm_custom_post_type_3d');

// Créer les taxonomies pour les réglages 3D 
function wpm_custom_taxonomies_3d() {
    $labels = array(
        'name' => 'Catégories 3D',
        'singular_name' => 'Catégorie 3D',
        'search_items' => 'Rechercher des catégories 3D',
        'all_items' => 'Toutes les catégories 3D',
        'edit_item' => 'Modifier la catégorie 3D',
        'update_item' => 'Mettre à jour la catégorie 3D',
        'add_new_item' => 'Ajouter une nouvelle catégorie 3D',
        'new_item_name' => 'Nom de la nouvelle catégorie 3D',
        'menu_name' => 'Catégories 3D',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => '3d-categorie'),
        'show_in_rest' => true,
    );

    register_taxonomy('3d-categorie', array('reglages_3d'), $args);
}
add_action('init', 'wpm_custom_taxonomies_3d');