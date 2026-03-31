<?php
/**
 * Plugin Name: Mana Must-Use Plugin
 * Description: Gestion du White Label
 * Version: 1.0
 * Author: Théo Manya
 */

if (!defined('ABSPATH')) exit;

define('MANA_CORE_DIR', plugin_dir_path(__FILE__) . 'core/');

// L'ordre est VITAL : config d'abord !
require_once MANA_CORE_DIR . 'config.php';
require_once MANA_CORE_DIR . 'admin-settings.php';
require_once MANA_CORE_DIR . 'white-label.php';