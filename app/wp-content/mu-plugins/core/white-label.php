<?php
/** 
 * APPLICATION DU WHITE LABEL ET NETTOYAGE
 */

// Masquer ACF
add_action('init', function() {
    add_filter('acf/settings/show_admin', '__return_false');
});

add_action('admin_menu', function () {
    if (wp_get_value('hide_articles')) remove_menu_page('edit.php');
    if (wp_get_value('hide_pages'))    remove_menu_page('edit.php?post_type=page');
    if (wp_get_value('hide_media'))    remove_menu_page('upload.php');
    if (wp_get_value('hide_comments')) remove_menu_page('edit-comments.php');
    if (wp_get_value('hide_plugins'))  remove_menu_page('plugins.php');
    if (wp_get_value('hide_themes')) remove_menu_page('themes.php');
    if (wp_get_value('hide_settings')) remove_menu_page('options-general.php');

    
    remove_submenu_page('index.php', 'update-core.php');
}, 999);

// Ton Logo Dashboard (M)
add_action('admin_head', function() {
    $icon_url = get_template_directory_uri() . '/icon.svg';
    echo '<style>
        #adminmenu #menu-dashboard .wp-menu-image:before { display: none !important; }
        #adminmenu #menu-dashboard .wp-menu-image {
            background: url("' . $icon_url . '") no-repeat !important;
            background-size: 20px 20px !important;
            background-position: 9px center !important;
        }
        .update-nag, .updated, .core-updates { display: none !important; }
        #toplevel_page_mana-settings .wp-menu-image:before { display: none !important; }
        #toplevel_page_mana-settings .wp-menu-image {
            background: url("' . $icon_url . '") no-repeat center !important;
            background-size: 18px 18px !important;
            opacity: 0.7;
        }
        #toplevel_page_mana-settings:hover .wp-menu-image { opacity: 1; }
    </style>';
    
    remove_action('admin_notices', 'update_nag', 3);
}, 1);
