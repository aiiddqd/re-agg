<?php

namespace ReAgg\PDP\LinkExternal;


add_filter('woocommerce_product_single_add_to_cart_text', __NAMESPACE__ . '\\single_add_to_cart_text');
remove_action('woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30);
add_action('woocommerce_external_add_to_cart', __NAMESPACE__ . '\\chg_woocommerce_external_add_to_cart', 10);

//Добавляем метабокс, который показывает число переходов
add_action('add_meta_boxes', function () {
    add_meta_box('wcpee_count', 'Переходы по ссылке', __NAMESPACE__ . '\\add_metabox', 'product', 'side');
});

add_filter('woocommerce_product_add_to_cart_url', __NAMESPACE__ . '\\replace_url_for_ext_product', 11, 2);
add_action('wp', __NAMESPACE__ . '\\redirect_to_url');

function redirect_to_url()
{
    global $wp;
    if('go' != $wp->request){
        return;
    }


    $id = (int)$_GET['id'] ?? null;
    if (empty($id)) {
        return;
    }

    $product = wc_get_product($id);

    if (empty($product)) {
        return;
    }

    if (!$url = get_post_meta($product->get_id(), '_product_url', true)) {
        return;
    }

    $url = apply_filters('pdp_link_external_redirect', $url, $product);

    $count = intval(get_post_meta($product->get_id(), 'pdp_link_external_count', true));
    $count++;
    update_post_meta($product->get_id(), 'pdp_link_external_count', $count);

    wp_redirect($url, 301);
    exit;
}



function replace_url_for_ext_product($url, $product)
{
    if ($product->get_type() !== 'external') {
        return $url;
    }

    $url = home_url('go');
    $url = add_query_arg('id', $product->get_id(), $url);
    return $url;
}



//Делаем ссылку с атрибутом target=_blank
function chg_woocommerce_external_add_to_cart()
{
    global $product;
    
    if ( ! $product->add_to_cart_url() ) {
        return;
    }

    include_once(__DIR__ . '/block.php');
}

/*
    * Выводим число переходов в метабоксе продукта
    */
function add_metabox()
{
    $post = get_post();
    $count = get_post_meta($post->ID, 'pdp_link_external_count', true);
    if(empty($count)){
        $count = 0;
    }
    echo "Количество: " . $count;
}


/**
 * Настройка текста кнопок
 */
function single_add_to_cart_text($text)
{

    $product = wc_get_product();

    if(empty($product)){
        return $text;
    }

    if ('simple' == $product->get_type()) {
        $text = 'Заказать';
    }

    if ('external' == $product->get_type() && empty($product->get_button_text())) {
        $text = 'Подробнее...';
    }


    return $text;
}
