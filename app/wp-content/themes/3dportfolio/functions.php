<?php
/**
 * CONFIGURATION THÈME CUSTOM
 */

/**
 * SÉCURITÉ & CLEANUP (REGLAGES DE BASE)
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
        #adminmenu #menu-dashboard .wp-menu-image img { display: none; }
        #adminmenu #menu-dashboard .wp-menu-image:before {
            content: "";
            background: url("' . get_template_directory_uri() . '/icon.svg") no-repeat center;
            background-size: 18px;
            opacity: 0.7;
            display: block;
            height: 100%;
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
    // Retire le menu Articles (Posts)
    remove_menu_page('edit.php');
    
    // Retire le menu Commentaires
    remove_menu_page('edit-comments.php');
    
    // Masquer le sous-menu Mises à jour
    remove_submenu_page('index.php', 'update-core.php');
}, 999);

add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
});


/**
 * Fonctionnalitées
 */

