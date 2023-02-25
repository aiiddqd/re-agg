<?php
/**
 * @var  array  $attributes Block attributes.
 * @var  array  $block Block data.
 * @var  string $context Preview context [editor,frontend].
 */

$post = get_post();

$url_internal = $attributes['url-internal'] ?? null;
$title = $attributes['name'] ?? null;
$excerpt = $attributes['excerpt'] ?? null;
$features = $attributes['features'] ?? null;
$url = $attributes['url'] ?? null;
if($url){
    $url = reagg_get_url_for_block($attributes['blockId'], $post->ID);
}
$image = $attributes['image'] ?? null;
if($image){
    $image = wp_get_attachment_image($image['id']);
}

if ($url_internal) {
    $product_id = url_to_postid($url_internal);
}

if($product_id){
    if (empty($image)) {
        $image = get_the_post_thumbnail($product_id);
    } 
    if (empty($title)) {
        $title = get_post($product_id)->post_title;
    }
    if (empty($excerpt)) {
        $excerpt = get_the_excerpt($product_id);
    }
    if (empty($features)) {
        $features = '';
    }
    if (empty($url)) {
        if ($product = wc_get_product($product_id)) {
            $url = wc_get_product($product_id)->add_to_cart_url();
        }
    }
}

$article_id = $attributes['blockId'];

?>
<div class="rating-item">
    <article id="<?= $article_id; ?>">
        <div class="rating-item-content">
            <figure class="post-thumbnail">
                <a href="<?= $url ?>" rel="bookmark" tabindex="-1" target="_blank" aria-hidden="true">
                    <?= $image ?>
                </a>
            </figure>


            <div class="entry-wrapper">
                <header>
                    <a href="<?= $url ?>" target="_blank">
                        <strong>
                            <?= $title ?>
                        </strong>
                    </a>
                </header><!-- .entry-header -->
                <div class="entry-excerpt">
                    <?= $excerpt ?>
                    <?= $features ?>
                </div><!-- .entry-content -->
                <div class="entry-meta">
                    <?php do_action('newspack_theme_entry_meta'); ?>
                </div><!-- .meta-info -->
            </div><!-- .entry-wrapper -->
        </div>
        <div class="cta">
            <!-- wp:buttons -->
            <div class="wp-block-buttons"><!-- wp:button -->
                <div class="wp-block-button">
                    <a class="wp-block-button__link wp-element-button" target="_blank" href="<?= $url ?>">Перейти на
                        сайт</a>
                </div>
                <!-- /wp:button -->
                <?php if ($url_internal): ?>
                    <!-- wp:button {"className":"is-style-outline"} -->
                    <div class="wp-block-button is-style-outline">
                        <a class="wp-block-button__link wp-element-button" href="<?= $url_internal ?>"
                            target="_blank">Обзор</a>
                    </div>
                    <!-- /wp:button -->
                <?php endif; ?>
            </div>
            <!-- /wp:buttons -->

        </div>

    </article><!-- #post-${ID} -->
</div>