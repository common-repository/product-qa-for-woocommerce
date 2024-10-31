<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://profiles.wordpress.org/vishalkakadiya/
 * @since      1.0.0
 *
 * @package    Product_Faq_For_Woocommerce
 * @subpackage Product_Faq_For_Woocommerce/admin
 */

/**
 * Add Woocommerce Setting at Woocommerce->Settings->Product
 *
 * @since 1.0.0
 *
 * @package    Product_Faq_For_Woocommerce
 * @subpackage Product_Faq_For_Woocommerce/admin
 * @author     Vishal Kakadiya <vishalkakadiya123@gmail.com>
 */
class Product_Faq_For_Woocommmerce_Settings {

    /**
     * Initialize the class and set hooks.
     *
     * @since    1.0.0
     * @access   public
     */
    public function __construct() {

        // Add new section in product tab
        add_filter('woocommerce_get_sections_products', array($this, 'section'), 10);

        // Add settings in new tab
        add_filter('woocommerce_get_settings_products', array($this, 'settings'), 10, 2);
    }

    /**
     * Add new section in Woocommerce->Settings->Products.
     *
     * This function is callback for woocommerce_get_sections_products.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $sections Woocommerce settings.
     *
     * @return array $sections Woocommerce settings.
     */
    public function section($sections) {

        $sections['product_faq'] = __('Product QA', 'product-faq-for-wc');
        return $sections;
    }

    /**
     * Add setting fields in section.
     *
     * This function is callback for woocommerce_get_settings_products.
     *
     * @param $settings
     * @param $current_section
     *
     * @return array
     */
    public function settings($settings, $current_section) {

        /**
         * Check the current section is what we want.
         *
         */
        if ('product_faq' === $current_section) {

            $settings = array();

            // Title
            $settings[] = array(
                'receive_quotation' => __('Category Wise Discount', 'product-faq-for-wc'),
                'type' => 'title',
                'desc' => __('<h3>Settings<h3>', 'product-faq-for-wc'),
                'id' => 'product_quotation'
            );

            $settings[] = array(
                'name' => __('Enable qa feature', 'product-faq-for-wc'),
                'desc_tip' => __('You want to enable qa feature on front-end side product detail page', 'product-faq-for-wc'),
                'id' => 'wc_product_faq_feature',
                'type' => 'checkbox',
                'css' => 'min-width:300px;',
                'desc' => __('Enable qa feature', 'product-faq-for-wc'),
            );

            // Get term list of product_cat taxonomy.
            $terms = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'fields' => 'id=>name'
            ));

            // Multi select
            $settings[] = array(
                'name' => __('Select Categories', 'product-faq-for-wc'),
                'desc_tip' => __('Multi-select categories, products with selected categories have QA feature.
									(Note : Empty selection, enables QA feature for all products.)', 'product-faq-for-wc'),
                'id' => 'wc_product_faq_category_list',
                'type' => 'multiselect',
                'options' => $terms,
                'css' => 'height: 150px; width:50%;',
                'desc' => __('Multi-select categories, products is selected categories have qa feature.
								(Note : Empty selection, enables QA feature for all products.)', 'product-faq-for-wc'),
            );

            // Textbox
            $settings[] = array(
                'name' => __('Sender email', 'product-faq-for-wc'),
                'desc' => __('Add sender email, by that email all commnunication works.', 'product-faq-for-wc'),
                'id' => 'wc_product_faq_sender_email',
                'type' => 'text',
                'css' => 'min-width:300px;'
            );

            $settings[] = array(
                'name' => __('Logo url', 'product-faq-for-wc'),
                'desc' => __('Add logo url which you want to add in email', 'product-faq-for-wc'),
                'id' => 'wc_product_faq_email_logo',
                'css' => 'width:50%;',
                'type' => 'text'
            );

            /* Question email's settings */
            $settings[] = array(
                'name' => __('Question email\'s title', 'product-faq-for-wc'),
                'desc' => __('Title which visible on top of question email.', 'product-faq-for-wc'),
                'id' => 'wc_product_faq_question_title',
                'type' => 'text',
                'css' => 'min-width:300px;'
            );
            $settings[] = array(
                'name' => __('Question email\'s subject', 'product-faq-for-wc'),
                'desc' => __('Subject which visible on top of question email.', 'product-faq-for-wc'),
                'id' => 'wc_product_faq_question_subject',
                'type' => 'text',
                'css' => 'min-width:300px;'
            );
            $settings[] = array(
                'name' => __('Question email\'s button text', 'product-faq-for-wc'),
                'desc' => __('Subject which visible on top of question email.', 'product-faq-for-wc'),
                'id' => 'wc_product_faq_question_button_text',
                'type' => 'text',
                'css' => 'min-width:300px;'
            );

            $settings[] = array(
                'type' => 'sectionend',
                'id' => 'product_quotation'
            );
        }

        return $settings;
    }

}
