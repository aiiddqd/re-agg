<?php
/**
 * @var  array  $attributes Block attributes.
 * @var  array  $block Block data.
 * @var  string $context Preview context [editor,frontend].
 */

$pros = explode("\n", $attributes['pros'] ?? []);
$cons = explode("\n", $attributes['cons'] ?? []);
?>

<div class="pac-wrapper">
    <header>
        <strong>Плюсы и минусы</strong>
    </header>
    <article>
        <div class="pac-list pac-pros">
            <strong><small>Плюсы</small></strong>
            <ul>
                <?php
                foreach ($pros as $item) {
                    printf('<li><span>%s</span></li>', $item);
                }
                ?>
            </ul>
        </div>
        <div class="pac-list pac-cons">
            <strong><small>Минусы</small></strong>
            <ul>
                <?php
                foreach ($cons as $item) {
                    printf('<li><span>%s</span></li>', $item);
                }
                ?>
            </ul>
        </div>
    </article>
</div>