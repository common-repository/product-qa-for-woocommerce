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
 * Managing 'Product' column in 'Product QA' table.
 *
 * @since 1.0.0
 *
 * @package    Product_Faq_For_Woocommerce
 * @subpackage Product_Faq_For_Woocommerce/admin
 * @author     Vishal Kakadiya <vishalkakadiya123@gmail.com>
 */
class Product_Faq_For_Woocommerce_Admin {

    /**
     * The post_type for Question and answers.
     *
     * @since    1.0.0
     * @access   private
     * @var      string     custom-post-type slug.
     */
    private $post_type = 'wc_product_faq';

    /**
     * Initialize the class and set hooks.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __construct() {

        // Add new column
        add_filter("manage_{$this->post_type}_posts_columns", array($this, 'column_title'));

        // Add new column content
        add_action("manage_{$this->post_type}_posts_custom_column", array($this, 'column_content'), 1, 2);
    }

    /**
     * Add "product" column in table.
     *
     * Callback function for manage_{$this->post_type}_posts_columns (filter).
     *
     * @since   1.0.0
     * @access  public
     *
     * @param   array $defaults column list.
     *
     * @return  array $defaults column list.
     */
    public function column_title($defaults) {
        $defaults['product'] = 'Product';

        return $defaults;
    }

    /**
     * Add content in column.
     *
     * Callback function for manage_{$this->post_type}_posts_custom_column (action).
     *
     * @since   1.0.0
     * @access  public
     *
     * @param string $column_name column name.
     * @param int    $post_id     post id.
     */
    public function column_content($column_name, $post_id) {

        if ('product' === $column_name) {
            $product_id = wp_get_post_parent_id($post_id);
            ?>
            <a href="<?php echo get_the_permalink($product_id) ?>">
                <?php echo get_the_title($product_id); ?>
            </a><?php
        }
    }

}
