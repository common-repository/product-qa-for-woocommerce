<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://profiles.wordpress.org/vishalkakadiya/
 * @since      1.0.0
 *
 * @package    Product_Faq_For_Woocommerce
 * @subpackage Product_Faq_For_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Product_Faq_For_Woocommerce
 * @subpackage Product_Faq_For_Woocommerce/includes
 * @author     Vishal Kakadiya <vishalkakadiya123@gmail.com>
 */
class Product_Faq_For_Woocommerce_i18n {

    /**
     * Initialize the class and set hooks.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __construct() {

        // Load text domain.
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
    }

    /**
     * Load the plugin text domain for translation.
     *
     * Callback function for plugins_loaded (action).
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
                'product-faq-for-wc', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

}
