<?php
/**
 * @var  array  $attributes Block attributes.
 * @var  array  $block Block data.
 * @var  string $context Preview context [editor,frontend].
 */


$url_internal = $attributes['url-internal'] ?? null;
if (empty($url_internal)) {
    return;
}

$post_id = url_to_postid($url_internal);
if (empty($post_id)) {
    return;
}

$title = get_post($post_id)->post_title;
$excerpt = $attributes['excerpt'] ?? null;
$features = $attributes['features'] ?? null;
$url = $attributes['url'] ?? null;
$image = $attributes['image'] ?? null;

if (empty($excerpt)) {
    $excerpt = get_the_excerpt($post_id);
}
if (empty($features)) {
    $features = '';
}
if (empty($url)) {
    $url = wc_get_product($post_id)->add_to_cart_url();
}

if (empty($image)) {
    $image = get_the_post_thumbnail($post_id);
} else {
    $image = wp_get_attachment_image($image['id']);
}


?>
<div class="rating-item">
    <article id="article-<?= $post_id; ?>">
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
                <!-- wp:button {"className":"is-style-outline"} -->
                <div class="wp-block-button is-style-outline">
                    <a class="wp-block-button__link wp-element-button" href="<?= $url_internal ?>"
                        target="_blank">Обзор</a>
                </div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->

        </div>

    </article><!-- #post-${ID} -->
</div>