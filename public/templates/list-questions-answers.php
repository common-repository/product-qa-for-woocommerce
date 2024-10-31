<?php
/**
 * Display question and answer list on woocommerce product detail page
 *
 * Includes in Product_Faq_For_Woocommerce_Public::faq_tab_content().
 *
 * @link       http://profiles.wordpress.org/vishalkakadiya/
 *
 * @package    Product_Faq_For_Woocommerce
 * @subpackage Product_Faq_For_Woocommerce/public/templates
 * @since      1.0.0
 */
?>

<h2><?php _e('Question Answers', 'product-faq-for-wc'); ?></h2>

<?php
global $product;

$brought = false;
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, $product->id)) {
        $brought = true;
    }
}


// Question Query
$question_query = new WP_Query(array(
    'post_type' => 'wc_product_faq',
    'post_parent' => $product->id
        ));


if ($question_query->have_posts()) {
    ?>
    <ul class="wc-faq-questions">
        <?php
        while ($question_query->have_posts()) {
            $question_query->the_post();
            $question_id = get_the_ID();
            ?>

            <li class="wc-questions">
                <div class="question-content">
                    <span class="question-symbol">Q</span>
                    <?php the_title(); ?>
                    <?php if ($brought) { ?>
                        <a data-id="<?php echo $question_id; ?>" class="answer-button">
                            <?php _e('Answer Now', 'product-faq-for-wc'); ?>
                        </a>
                        <div id="<?php echo 'question-' . $question_id; ?>" class="answer-slide">
                            <form method="post">
                                <input type="text" placeholder="<?php _e('Your answer...', 'product-faq-for-wc'); ?>"  name="product_answer" class="field-answer" />
                                <input type="hidden" name="question_id" value="<?php echo $question_id; ?>" />
                                <input type="submit" name="wc_give_answer" value="Answer" />
                            </form>
                        </div>
                    <?php } ?>
                </div>
                <?php
                $comments = get_approved_comments($question_id);
                if (!empty($comments)) {
                    ?>
                    <ul class="wc-faq-answers">
                        <?php foreach ($comments as $comment) { ?>
                            <li class="wc-answers">
                                <span class="answer-symbol"><?php _e('A', 'product-faq-for-wc'); ?></span>
                                <span class="answer"><?php echo $comment->comment_content; ?></span>
                            </li>
                        <?php } ?>
                    </ul><?php
                }
                ?>
            </li>
        <?php } ?>
    </ul>
    <?php
    /* Restore original Post Data */
    wp_reset_postdata();
} else {
    _e('Not any questions yet, be the first to ask question ?', 'product-faq-for-wc');
}


if (is_user_logged_in()) :
    ?>
    <h3><?php _e('Ask Question Now!', 'product-faq-for-wc'); ?></h3>
    <form method="post">
        <input type="text" placeholder="<?php _e('Your question ?', 'product-faq-for-wc'); ?>" name="product_question" class="field-question" />
        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>" />
        <input type="submit" name="wc_ask_question" value="<?php _e('Ask Now', 'product-faq-for-wc'); ?>" />
    </form><?php
else:
    _e('Please login to to ask questions...... now', 'product-faq-for-wc');
endif;
?>