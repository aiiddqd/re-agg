<?php

namespace ReAgg\Optimizatron {


    add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\frontend');
    add_action('admin_bar_menu', __NAMESPACE__ . '\clear_titles', 999);


    add_action('admin_init', function () {
        add_settings_field(
            $id = 'enable_optimizatron',
            $title = 'Optimizatron',
            $callback = function () {
                $value = \ReAgg\Config\get()['enable_optimizatron'] ?? null;
                $name = sprintf('%s[%s]', \ReAgg\Config\OPTION_KEY, 'enable_optimizatron');
                printf('<input type="checkbox" name="%s" value="1" %s />', $name, checked(1, $value, false));
            }
            ,
            \ReAgg\Config\OPTION_PAGE
        );

    });

    function is_enable()
    {
        return \ReAgg\Config\get()['enable_optimizatron'] ?? false;
    }

    function frontend()
    {
        if (!is_enable()) {
            return;
        }

        if (!current_user_can('administrator')) {
            return;
        }

        $file_path = '/style.css';
        $file_path_abs = __DIR__ . $file_path;
        $file_url = plugins_url($file_path, __FILE__);
        wp_enqueue_style('app-admin-bar', $file_url, [], filemtime($file_path_abs));

    }


    function clear_titles(\WP_Admin_Bar $wp_admin_bar)
    {
        if (!is_enable()) {
            return;
        }

        $clear_titles = array(
            'site-name',
            'customize',
            'my-sites',
            'edit',
            // 'google-site-kit',
            // 'new-content',
        );

        $nodes = $wp_admin_bar->get_nodes();
        foreach ($nodes as $key => $node) {


            if (in_array($node->id, $clear_titles)) {
                // use the same node's properties
                $args = $node;

                // make the node title a blank string
                $args->title = '';

                // update the Toolbar node
                $wp_admin_bar->add_node($args);
            }

        }

    }
}