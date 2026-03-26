<?php
/** 
 * LOGIQUE DE VERROUILLAGE DES ÉTATS
 */

function wp_is_locked($option) {
    $constant = strtoupper($option);
    return defined($constant);
}

function wp_get_value($option) {
    // Priorité absolue à la constante (dans le code oiu dans le .env)
    if (wp_is_locked($option)) {
        return constant(strtoupper($option));
    }
    // Sinon, on pioche dans la base de données
    return get_option('mana_mu_settings_' . $option, false);
}
