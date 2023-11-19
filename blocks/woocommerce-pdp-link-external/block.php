<?php 

namespace ReAgg\PDP\LinkExternal;

global $product;

$url = $product->add_to_cart_url();

if (empty($url)) {
    return;
}

$button_text = $product->single_add_to_cart_text();

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="cart" action="<?php echo esc_url( $url ); ?>" method="get" target="_blank">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<button type="submit" class="single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php echo esc_html( $button_text ); ?></button>

	<?php wc_query_string_form_fields( $url ); ?>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
