<?php
/**
 * url cpa redirects
 * 
 * plugin sURL
 */

namespace ReAgg\SurlRedirector {
    add_filter('reagg/rating_item_url', __NAMESPACE__ . '\\url_replace_to_cpa_format');
    add_filter('pdp_link_external_redirect', __NAMESPACE__ . '\\url_replace_to_cpa_format', 11);

    add_action('init', __NAMESPACE__ . '\\add_acf');

    // add_action('template_redirect', __NAMESPACE__ . '\\redirect_by_rbid');

    function url_replace_to_cpa_format($url)
    {

        $url_parts = wp_parse_url($url);
        $data = get_data();

        $domain_of_url = $url_parts['host'] ?? null;
        if( ! in_array($domain_of_url, $data['domains'])){
            return $url;
        }


        $args = $data['items'][$domain_of_url];


        if (!empty($args['url_template_to_redirect'])) {
            $url = sprintf($args['url_template_to_redirect'], $url);
        }

        if (!empty($args['query_arg_key'])) {
            $url = add_query_arg($args['query_arg_key'], $args['query_arg_value'], $url);
        }

        // echo '<pre>';
        // var_dump($args);
        // var_dump($url);
        // var_dump($data);
        // exit;
        return $url;
    }


    //redirect by url?rbid=sdfsdf
    function redirect_by_rbid()
    {

        if (!$post = get_post()) {
            return;
        }

        if (!$block_id = $_GET['rbid'] ?? false) {
            return;
        }

        $url = get_url_by_block_id($block_id);

        $url = apply_filters('url_blocks_redirect', $url, $post, $url);

        $deeplinks = get_deeplinks();
        $url_parts = wp_parse_url($url);
        $host = $url_parts['host'] ?? '';

        $url = apply_filters('url_template_convert', $url, $deeplinks[$host], $host);

        $url = apply_filters('app_redirect_external_url', $url, $post, $block_id);

        if (!$url = wp_http_validate_url($url)) {
            return;
        }
        wp_redirect($url, 301);
        exit;

    }


    function get_deeplinks()
    {
        $args = [
            'post_type' => 'surl',
            'post_status' => 'publish',
            'numberposts' => -1,
        ];

        $urls = get_posts($args);

        $items = [];
        foreach ($urls as $url) {
            $url_redirect = get_post_meta($url->ID, '_surl_redirect', true);

            $url_redirect_parts = wp_parse_url($url_redirect);
            if (empty($url_redirect_parts['host'])) {
                continue;
            }

            $items[$url->post_title]['url'] = $url_redirect;
            $items[$url->post_title]['surl_post_id'] = $url->ID;
            $items[$url->post_title]['redirect_host'] = $url_redirect_parts['host'];
        }

        return $items;
    }

    function get_url_by_block_id($incoming_id)
    {
        if (empty($incoming_id)) {
            return false;
        }

        if (!$post = get_post()) {
            return false;
        }

        $blocks = parse_blocks($post->post_content);

        foreach ($blocks as $block) {
            if ($block['blockName'] != 'lazyblock/review-item') {
                continue;
            }
            if (!$block_id = $block['attrs']['blockId'] ?? false) {
                continue;
            }

            if ($block_id != $incoming_id) {
                continue;
            }

            $url = $block['attrs']['button-link'] ?? '';
            if (!$url = wp_http_validate_url($url)) {
                continue;
            }
            return $url;
        }

        return false;
    }

    function get_external_url_with_redirect($incoming_id)
    {
        if (empty($incoming_id)) {
            return false;
        }
        if (!$post = get_post()) {
            return false;
        }

        $url = get_permalink($post);
        $url = add_query_arg('rbid', $incoming_id);
        return $url;
    }



    function get_data()
    {
        $args = [
            'post_type' => 'surl',
            'post_status' => 'publish',
            'numberposts' => -1,
        ];

        $urls = get_posts($args);

        $data = [];
        $data['domains'] = [];
        $data['items'] = [];
        foreach ($urls as $url) {
            $url_redirect = get_post_meta($url->ID, '_surl_redirect', true);

            $domain = $url->post_title;
            $data['domains'][] = $domain;
            $url_redirect_parts = wp_parse_url($url_redirect);
            if (empty($url_redirect_parts['host'])) {
                continue;
            }

            $data['items'][$domain] = [
                'url' => $url_redirect,
                'surl_post_id' => $url->ID,
                'redirect_host' => $url_redirect_parts['host'],
                'query_arg_key' => get_post_meta($url->ID, 'query_arg_key', true),
                'query_arg_value' => get_post_meta($url->ID, 'query_arg_value', true),
                'url_template_to_redirect' => get_post_meta($url->ID, 'url_template_to_redirect', true),
                'domains' => get_post_meta($url->ID, 'domains', true),
            ];

            if ($domains = $data['items'][$domain]['domains']) {
                if ($domains = explode(',', $domains)) {
                    $domains = array_map('trim', $domains);
                    $data['domains'] = array_merge($data['domains'], $domains);
                }
            }
        }

        return $data;
    }


    function add_acf(){

        // var_dump(1); exit;

        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_60f3ebed30181',
                'title' => 'surl and refs',
                'fields' => array(
                    array(
                        'key' => 'field_62563fdf14478',
                        'label' => 'url template to redirect',
                        'name' => 'url_template_to_redirect',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => 'as sprintf(), for example:
            <br>- https://go.acstat.com/ad50a1d1309dc271?dl=%s
            <br>- https://ad.admitad.com/g/nml4qrv3eodb2ea472b4a4339b0b6f/?ulp=%s
            <br>- %s/ref/123243',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_60fc0bc58baef',
                        'label' => 'query_arg',
                        'name' => 'query_arg',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => 'for add_query_arg()',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_60fc0bfb8baf0',
                                'label' => 'key',
                                'name' => 'key',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '44',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                            array(
                                'key' => 'field_60fc0c038baf1',
                                'label' => 'value',
                                'name' => 'value',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '44',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_6256437886700',
                        'label' => 'domains',
                        'name' => 'domains',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => 'separated by commas, if different from the one specified in the title',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_60f3ec0705da4',
                        'label' => 'note',
                        'name' => 'note',
                        'aria-label' => '',
                        'type' => 'textarea',
                        'instructions' => 'где партнерка? мыло? вход?',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => '',
                        'new_lines' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'surl',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ));
            
        endif;
    }
    
}
