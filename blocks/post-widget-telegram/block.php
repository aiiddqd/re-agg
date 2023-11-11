<?php

/**
 * @var  array  $attributes Block attributes.
 * @var  array  $block Block data.
 * @var  string $context Preview context [editor,frontend].
 */


$data = new class($attributes){
    public $path = null;

    function __construct($attributes){
        $url = $attributes['url'];
        $url_parts = wp_parse_url($url);
        $this->path = ltrim( $url_parts['path'], '/' );
    }
};

if(empty($data->path)){
    echo "no path for Telegram post";
    return;
}

?>
<div class="lb-telegram-widget">
    <script async src="https://telegram.org/js/telegram-widget.js?15" data-telegram-post="<?= $data->path ?>" data-width="100%" data-userpic="false">
    </script>
</div>