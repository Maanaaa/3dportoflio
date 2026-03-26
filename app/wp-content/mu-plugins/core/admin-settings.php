<?php
/** 
 * INTERFACE DE RÉGLAGES DU SYSTÈME MANA
 */

add_action('admin_menu', function() {
    // add_menu_page crée un onglet principal à gauche
    add_menu_page(
        'Configuration Mana',
        'Configuration',
        'manage_options',
        'mana-settings',
        'mana_render_settings',
        'dashicons-admin-generic',

        99 // Position              
    );
});



function mana_render_settings() {
    if (!current_user_can('manage_options')) return;
    $features = [
        'hide_articles' => 'Articles',
        'hide_pages'    => 'Pages',
        'hide_media'    => 'Médias',
        'hide_comments' => 'Commentaires',
        'hide_plugins'  => 'Extensions (Plugins)',
        'hide_themes'   => 'Apparence',
        'hide_settings' => 'Règlages',

    ];

    ?>
    <div class="wrap">
        <h1>Configuration Système Mana</h1>
        <hr>
        <form method="post" action="options.php">
            <?php 
                settings_fields('mana_mu_group'); 
                do_settings_sections('mana-settings');
            ?>
            <table class="form-table">
                <?php foreach ($features as $id => $label) : 
                    $locked = wp_is_locked($id);
                    $value = wp_get_value($id);
                ?>
                <tr>
                    <th scope="row"><?php echo $label; ?></th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" 
                                   name="mana_mu_settings_<?php echo $id; ?>" 
                                   value="1" 
                                   <?php checked($value, 1); ?>
                                   <?php if ($locked) echo 'disabled'; ?>>
                            
                            <?php if ($locked) : ?>
                                <span style="color: #d63638; font-weight: bold; margin-left: 10px;">
                                    🔒 Verrouillé par le code (wp-config.php)
                                    Documentation : <a href="https://github.com/Maanaaa/3dportoflio">https://github.com/Maanaaa/3dportoflio</a>
                                </span>
                            <?php endif; ?>
                        </label>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php submit_button('Enregistrer les modifications'); ?>
        </form>
    </div>
    <?php
}

// On enregistre chaque option dans WordPress
add_action('admin_init', function() {
    $options = ['hide_articles', 'hide_pages', 'hide_media', 'hide_comments', 'hide_plugins', 'hide_themes', 'hide_settings'];
    foreach ($options as $opt) {
        register_setting('mana_mu_group', 'mana_mu_settings_' . $opt);
    }
});