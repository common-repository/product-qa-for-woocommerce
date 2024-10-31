<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://profiles.wordpress.org/vishalkakadiya/
 * @since             1.0.0
 * @package           Product_Faq_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Product QA For Woocommerce
 * Plugin URI:        https://wordpress.org/plugins/product-qa-for-woocommerce/
 * Description:       Display Question and Answer of product where user/visitor can ask question and Store Owner will reply on product queries.
 * Version:           1.0.3
 * Author:            Vishal Kakadiya
 * Author URI:        http://profiles.wordpress.org/vishalkakadiya/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       product-faq-for-wc
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-product-faq-for-woocommerce-activator.php
 */
function activate_product_faq_for_woocommerce() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-product-faq-for-woocommerce-activator.php';
    Product_Faq_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-product-faq-for-woocommerce-deactivator.php
 */
function deactivate_product_faq_for_woocommerce() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-product-faq-for-woocommerce-deactivator.php';
    Product_Faq_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_product_faq_for_woocommerce');
register_deactivation_hook(__FILE__, 'deactivate_product_faq_for_woocommerce');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-product-faq-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_product_faq_for_woocommerce() {

    new Product_Faq_For_Woocommerce();
}

run_product_faq_for_woocommerce();
