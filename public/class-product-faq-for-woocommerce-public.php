<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://profiles.wordpress.org/vishalkakadiya/
 * @since      1.0.0
 *
 * @package    Product_Faq_For_Woocommerce
 * @subpackage Product_Faq_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Product_Faq_For_Woocommerce
 * @subpackage Product_Faq_For_Woocommerce/public
 * @author     Vishal Kakadiya <vishalkakadiya123@gmail.com>
 */
class Product_Faq_For_Woocommerce_Public {

    /**
     * The post_type for Question and answers.
     *
     * @since    1.0.0
     * @access   private
     * @var      string     custom-post-type slug
     */
    private $post_type = 'wc_product_faq';

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __construct() {

        // Enqueue scripts.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Register post type
        add_action('init', array($this, 'register_custom_post_type'));

        // Add FAQ tab on product detail page.
        add_filter('woocommerce_product_tabs', array($this, 'faq_tab'));

        // Save question for product
        add_action('init', array($this, 'save_question'));

        // Save answer for product
        add_action('init', array($this, 'save_answer'));
    }

    /**
     * Enqueue scripts.
     *
     * Callback function for wp_enqueue_scripts (action).
     *
     * @since   1.0.0
     * @access  public
     */
    public function enqueue_scripts() {

        // front-end side css
        wp_enqueue_style('wc-product-faq-public-css', plugin_dir_url(__FILE__) . 'css/faq-public.css');

        // front-end side js
        wp_enqueue_script('wc-product-faq-public-js', plugin_dir_url(__FILE__) . 'js/faq-public.js', array('jquery'), '1.0.0', false);
    }

    /**
     * Register a custom post type called "wc_product_faq".
     *
     * Callback function for init (action).
     *
     * @since   1.0.0
     * @access  public
     *
     * @link https://developer.wordpress.org/reference/functions/register_post_type/
     */
    public function register_custom_post_type() {
        $args = array(
            'public' => true,
            'label' => 'Product QA',
            'rewrite' => array('slug' => $this->post_type),
            'supports' => array('title', 'editor', 'author', 'comments'),
        );
        register_post_type($this->post_type, $args);
    }

    /**
     * Adding QA tab on product detail page.
     *
     * Callback function for woocommerce_product_tabs (filter).
     *
     * @since   1.0.0
     * @access  public
     *
     * @ref https://docs.woocommerce.com/document/editing-product-data-tabs/
     *
     * @param array $tabs woocommerce product detail page's tabs.
     *
     * @return array $tabs woocommerce product detail page's tabs.
     */
    public function faq_tab($tabs) {

        $faq_feature_enable = get_option('wc_product_faq_feature');

        if ($faq_feature_enable) {
            global $product;

            $enable_feature = false;
            $categories = get_option('wc_product_faq_category_list');
            if (isset($categories) && !empty($categories)) {
                $terms = get_the_terms($product->id, 'product_cat');

                if ($terms) {
                    foreach ($terms as $term) {
                        if (in_array($term->term_id, $categories)) {

                            $enable_feature = true;
                            break;
                        }
                    }
                }
            } else {
                $enable_feature = true;
            }

            if ($enable_feature) {
                // Change tab name which you want
                $tab_name = apply_filters('wc_product_faq_tab_title', 'QA');

                // Adds the new tab
                $tabs['faq_tab'] = array(
                    'title' => __($tab_name, 'product-faq-for-wc'),
                    'priority' => 50,
                    'callback' => array($this, 'faq_tab_content')
                );
            }
        }

        return $tabs;
    }

    /**
     * QA tab content, Display question and answers on product detail page.
     *
     * @since 1.0.0
     * @access public
     *
     * @see public/templates/list-questions-answers.php
     */
    public function faq_tab_content() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/templates/list-questions-answers.php';
    }

    /**
     * Save question for particular product.
     *
     * Callback function for init (action).
     *
     * @see    wp_insert_post()
     * @see    set_question_email()
     *
     * @since  1.0.0
     * @access public
     */
    public function save_question() {
        if (isset($_POST['wc_ask_question'])) {
            if (isset($_POST['product_question']) && isset($_POST['product_id'])) {
                $question = sanitize_text_field($_POST['product_question']);
                $product_id = absint($_POST['product_id']);

                // Manage question status which user asked.
                $question_status = apply_filters('wc_product_faq_manage_question_status', 'publish');

                // Create object
                $question_data = array(
                    'post_title' => $question,
                    'post_content' => $question,
                    'post_status' => $question_status,
                    'post_author' => get_current_user_id(),
                    'post_type' => $this->post_type,
                    'post_parent' => $product_id
                );

                // Insert the post into the database
                $post_id = wp_insert_post($question_data);

                if (!is_wp_error($post_id)) {
                    $this->set_question_email($product_id, $question);

                    // Do something after question successfully added
                    do_action('wc_product_faq_after_question_added');
                }
            }
        }
    }

    /**
     * Getting question email settings.
     *
     * @since 1.0.0
     * @access private
     *
     * @see   create_mail()
     *
     * @param int $product_id Current product id.
     * @param string $question Setting user's email settings.
     */
    private function set_question_email($product_id, $question) {
        $title = get_option('wc_product_faq_question_title');
        $subject = get_option('wc_product_faq_question_subject');
        $content = __('<strong>Question : </strong>', 'product-faq-for-wc') . $question;
        $button_label = get_option('wc_product_faq_question_button_text');

        $this->create_mail($product_id, $title, $subject, $content, $button_label);
    }

    /**
     * Set email template.
     *
     * @since 1.0.0
     * @access private
     *
     * @see customer_emails()
     * @see email_template()
     * @see send_mail()
     *
     * @param int    $product_id    Current product id.
     * @param string $title         Title of email.
     * @param string $subject       Subject of email.
     * @param string $content       Content of email.
     * @param string $button_label  Button label text.
     */
    private function create_mail($product_id, $title, $subject, $content, $button_label) {
        $mail_orders = $this->customer_emails($product_id);

        if (!empty($mail_orders)) {

            $message = $this->email_template($product_id, $title, $content, $button_label);

            foreach ($mail_orders as $mail_order) {
                $this->send_mail($mail_order['email'], $subject, $message);
            }
        }
    }

    /**
     * Return Customer email list who purchase this product.
     *
     * @since 1.0.0
     * @access private
     *
     * @param int $product_id Current product id.
     *
     * @return array $customers Customer list who purchase particular product.
     */
    private function customer_emails($product_id) {
        global $wpdb;

        $order_query = "SELECT order_id FROM {$wpdb->prefix}woocommerce_order_itemmeta woim
						LEFT JOIN {$wpdb->prefix}woocommerce_order_items oi
						ON woim.order_item_id = oi.order_item_id
						WHERE meta_key = '_product_id' AND meta_value = %d
						GROUP BY order_id";

        $order_ids = $wpdb->get_col($wpdb->prepare($order_query, $product_id));

        $customers = array();
        if (!empty($order_ids)) {

            // Manage order status, based on that emails will send.
            $order_status = apply_filters('wc_product_faq_manage_order_status', 'wc-completed');

            foreach ($order_ids as $order_id) {
                $order = new WC_Order($order_id);

                if ($order_status === $order->post_status) {
                    $order_email = $order->billing_email;

                    if (!in_array($order_email, $customers)) {
                        $customers[] = array(
                            'email' => $order_email,
                            'first_name' => $order->billing_first_name,
                            'last_name' => $order->billing_last_name
                        );
                    }
                }
            }
        }

        // Want to modify or add extra users in sending emails.
        return apply_filters('wc_product_faq_manage_customer_emails', $customers);
    }

    /**
     * Email template creation
     *
     * @since 1.0.0
     * @access private
     *
     * @see  public/templates/email/question.php.
     *
     * @param int    $product_id    Current product id.
     * @param string $title         Title of email.
     * @param string $content       Content of email.
     * @param string $button_label  Button label text.
     *
     * @return string $template Email body including HTML.
     */
    private function email_template($product_id, $title, $content, $button_label) {
        ob_start();

        require_once plugin_dir_path(dirname(__FILE__)) . 'public/templates/email/question.php';

        $template = ob_get_clean();

        return $template;
    }

    /**
     * Sending emails.
     *
     * @since 1.0.0
     * @access private
     *
     * @param string $to Email address.
     * @param string $subject Email subject.
     * @param string $message Email content.
     */
    private function send_mail($to, $subject, $message) {

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html; charset=UTF-8;\r\n";

        $sender_name = get_option('blogname');
        $sender = get_option('wc_product_faq_sender_email');
        if ($sender) {
            $headers .= "From: " . $sender_name . " <" . $sender . ">\r\n";
        }

        wp_mail($to, $subject, $message, $headers);
    }

    /**
     * Save answer for particular question.
     *
     * Callback function for init (action).
     *
     * @see wp_insert_comment()
     *
     * @since 1.0.0
     * @access public
     */
    public function save_answer() {
        if (isset($_POST['wc_give_answer'])) {
            if (isset($_POST['product_answer']) && isset($_POST['question_id'])) {
                $answer = sanitize_text_field($_POST['product_answer']);
                $question_id = absint($_POST['question_id']);

                // Manage answer status which user replied.
                $answer_status = apply_filters('wc_product_faq_manage_answer_status', 0);

                $current_user = wp_get_current_user();

                $data = array(
                    'comment_post_ID' => $question_id,
                    'comment_author' => $current_user->data->display_name,
                    'comment_author_email' => $current_user->data->user_email,
                    'comment_content' => $answer,
                    'user_id' => $current_user->ID,
                    'comment_date' => date('Y-m-d H:i:s'),
                    'comment_approved' => $answer_status
                );

                $comment_id = wp_insert_comment($data);
                if ($comment_id) {
                    do_action('wc_product_faq_after_answer_added');
                }
            }
        }
    }

}
