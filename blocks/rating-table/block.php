<?php

global $post;

$blocks = parse_blocks( $post->post_content );

$items = [];
foreach ( $blocks as $item ) {

	if ( empty( $item['blockName'] ) ) {
		continue;
	}

	if ( $item['blockName'] == 'lazyblock/rating-item' ) {
		$items[] = $item;
	}
	if ( isset( $item['innerBlocks'] ) ) {
		foreach ( $item['innerBlocks'] as $item ) {
			if ( $item['blockName'] == 'lazyblock/rating-item' ) {
				$items[] = $item;
			}
		}
	}

}

echo '<div class="rating-table-items">';
foreach ( $items as $item ):
	$name = $item['attrs']['name'] ?? null;
	$excerpt_item = $item['attrs']['excerpt'] ?? null;
	$features = $item['attrs']['features'] ?? '';
	$url = $item['attrs']['url'] ?? null;
	if ( $url ) {
		$url = reagg_get_url_for_block( $item['attrs']['blockId'], $post->ID );
	}

	$image = $item['attrs']['image'] ?? null;
	if ( $image ) {
		$image = json_decode( urldecode( $image ), true );
		$image = wp_get_attachment_image( $image['id'] );
	}

	$url_internal = $item['attrs']['url-internal'] ?? null;
	if ( $url_internal ) {
		$post_id = url_to_postid( $url_internal );
		$product = wc_get_product( $post_id );
	}
	if ( $product ) {
		if ( empty( $name ) ) {
			$name = get_the_title( $post_id );
		}

		if ( empty( $excerpt_item ) ) {
			$excerpt_item = get_the_excerpt( $post_id );
		}
		if ( empty( $url ) ) {
			$url = $product->add_to_cart_url();

		}

		if ( empty( $image ) ) {
			$image = get_the_post_thumbnail( $post_id );
		}
	}


	$article_id = '#' . $item['attrs']['blockId'];

	$post = get_post( $post_id );
    ?>
        <div class="rating-table-item">
            <div class="rating-table-item-content">
                <div class="rating-table-inner--title">
                    <a target="_blank" href="<?= $url ?>">
                        <strong>
                            <?= $name ?>
                        </strong>
                    </a>
                </div>
                <?php if ( $features ) : ?>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tags"
                            viewBox="0 0 16 16">
                            <path
                                d="M3 2v4.586l7 7L14.586 9l-7-7H3zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586V2z">
                            </path>
                            <path
                                d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1v5.086z">
                            </path>
                        </svg>
                        <small>
                            <?= $features ?>
                        </small>
                    </div>
                <?php else : ?>
                    <div class="rating-table--excerpt">
                        <small>
                            <?= $excerpt_item ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>
            <div class="wp-block-buttons rating-table-item-actions">
                <div class="wp-block-button mod-review">
                    <a class="wp-element-button wp-block-button__link" href="<?= $article_id ?>">
                        <small>Детали</small>
                    </a>
                </div>
                <div class="wp-block-button is-style-outline is-rating-table-main-btn">
                    <form class="app-cart" action="<?php echo esc_url( $url ); ?>" method="get" target="_blank">
                        <button type="submit" class="wp-element-button">
                            <strong>Сайт</strong>   
                        </button>
                        <?php wc_query_string_form_fields( $url ); ?>
                    </form>
                </div>
            </div>
        </div>

	<?php
endforeach;
echo '</div>';
