<?php
/**
 * Email template for question.
 *
 * Includes in Product_Faq_For_Woocommerce_Public::email_template().
 *
 * @link       http://profiles.wordpress.org/vishalkakadiya/
 *
 * @package    Product_Faq_For_Woocommerce
 * @subpackage Product_Faq_For_Woocommerce/public/templates
 * @since      1.0.0
 */
?>
<!DOCTYPE html>
<html>
    <body>
        <header style="text-align:center">
            <h1 style="background-color:#2F4F4F;border:1px solid rgb(204,204,204);margin-bottom:0;padding:12px;text-align:center;height:50px;">
                <?php
                $logo = get_option('wc_product_faq_email_logo');
                if ($logo) :
                    ?>
                    <img src="<?php echo $logo; ?>" style="height: 50px;"/><?php
                else:
                    echo get_option('blogname');
                endif;
                ?>
            </h1>
        </header>

        <?php $url = get_permalink($product_id); ?>

        <div style="border:1px solid #cccccc;border-top:0;padding:35px 35px 15px;position: relative;display: flex;">
            <div style="width: 100%;">
                <?php
                if (has_post_thumbnail($product_id)) :
                    $attachment_ids = get_post_thumbnail_id($product_id);
                    $attachment = wp_get_attachment_image_src($attachment_ids, 'shop_thumbnail');
                    ?>
                    <div style="width: 25%; float: left;">
                        <div>
                            <a href="<?php echo $url; ?>" alt="<?php echo get_the_title($product_id); ?>">
                                <img src="<?php echo $attachment[0]; ?>" class="card-image"/>
                            </a>
                        </div>
                        <h4 style="text-align: center; margin-top: 10px; font-size: 16px;">
                            <?php echo get_the_title($product_id); ?>
                        </h4>
                    </div>
                <?php endif; ?>

                <div style="box-sizing: border-box;float: right;width: 70%;">
                    <h3 style="font-size: 20px; margin: 0 0 20px 0;">
                        <?php echo $title; ?>
                    </h3>

                    <div style="margin-bottom:20px">
                        <p style="font-size:14px">
                            <?php echo $content; ?>
                        </p>
                    </div>

                    <div style="margin-top:35px">
                        <a href="<?php echo $url; ?>" style="background-color:rgb(215,73,55);border:1px solid rgb(0,0,0);color:rgb(255,255,255);cursor: pointer;font-weight:bold;padding:6px 12px;text-decoration:none;">
                            <?php echo $button_label; ?>
                        </a>
                    </div>
                </div>
                <br style="clear:both">
            </div>
        </div>
    </body>
</html>