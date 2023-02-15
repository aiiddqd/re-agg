<?php 

add_action( 'lzb/init', function() {

    lazyblocks()->add_block( array(
        'id' => 58883,
        'title' => 'Pros and Cons',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M9.01 14H2v2h7.01v3L13 15l-3.99-4v3zm5.98-1v-3H22V8h-7.01V5L11 9l3.99 4z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/pros-and-cons',
        'description' => '',
        'category' => 'media',
        'category_label' => 'media',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'html' => false,
            'multiple' => true,
            'inserter' => true,
        ),
        'ghostkit' => array(
            'supports' => array(
                'spacings' => false,
                'display' => false,
                'scrollReveal' => false,
                'frame' => false,
                'customCSS' => false,
            ),
        ),
        'controls' => array(
            'control_2329f84875' => array(
                'type' => 'textarea',
                'name' => 'pros',
                'default' => '',
                'label' => 'pros',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
                'multiline' => 'true',
            ),
            'control_1f9bed491b' => array(
                'type' => 'textarea',
                'name' => 'cons',
                'default' => '',
                'label' => 'cons',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
                'multiline' => 'true',
            ),
        ),
        'code' => array(
            'output_method' => 'template',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'condition' => array(
        ),
    ) );
    
} );