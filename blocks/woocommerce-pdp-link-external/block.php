<?php 

namespace ReAgg\PDP\LinkExternal;

global $product;

$url = get_product_url_external($product->get_id());

if (empty($url)) {
    return;
}
$button_text = $product->single_add_to_cart_text();

?>

<?php 

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<p class="cart">
        <a href="<?= $url ?>" target="_blank" rel="nofollow" class="single_add_to_cart_button button alt">
            <?= esc_html($button_text); ?>
        </a>
    </p>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

<?php do_action( 'woocommerce_after_add_to_cart_form') ?>