<?php
/*
* Plugin Name: @ Re Agg
* Description: Gutenberg Blocks for review sites and aggregators
* Author: uptimizt
* GitHub Plugin URI: https://github.com/uptimizt/re-agg/
* Version: 0.230210
*/

namespace ContentKit\LazyBlocks;

add_filter('lzb/block_render/include_template', __NAMESPACE__ . '\\' . 'chg_template_path', 10, 4);
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\' . 'frontend');
add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\\' . 'backend');
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\' . 'commone_style');
blocks_load_configs();


function blocks_load_configs()
{
  $files = glob(__DIR__ . '/blocks/*/config.php');
  foreach ($files as $file) {
    require_once $file;
  }
}

function backend()
{
  $files = glob(__DIR__ . '/blocks/*/block.css');
  foreach ($files as $file) {
    $version = filemtime($file);
    $block_name = basename(dirname($file));
    $rel_path = str_replace(plugin_dir_path(__FILE__), '', $file);
    $url = plugins_url($rel_path, __FILE__);
    wp_enqueue_style($block_name . '-style', $url, [], $version);
}
}

function frontend()
{
  $files = glob(__DIR__ . '/blocks/*/block.css');
  $post = get_post();

  foreach ($files as $file) {
    $version = filemtime($file);
    $block_name = basename(dirname($file));
    $block_name_lb = 'lazyblock/' . $block_name;
    if (has_block($block_name, $post) or has_block($block_name_lb, $post)) {
      $rel_path = str_replace(plugin_dir_path(__FILE__), '', $file);
      $url = plugins_url($rel_path, __FILE__);
      wp_enqueue_style($block_name . '-style', $url, [], $version);
    }
  }
}

function commone_style()
{
  $path = 'assets/commone.css';
  $url = plugins_url($path, __FILE__);
  $path = __DIR__ . '/' . $path;
  if (file_exists($path)) {
    wp_enqueue_style(
      'style-lbu7',
      $url,
      $dep = [],
      $var = filemtime($path)
    );
  }
}

function chg_template_path($template, $attributes, $block, $context)
{
  $plugin_path = sprintf('%s/%s/block.php', __DIR__, $block['slug']);
  $plugin_path = str_replace('lazyblock/', 'blocks/', $plugin_path);
  // echo $plugin_path;
  if (file_exists($plugin_path)) {
    return $plugin_path;
  }

  return $template;
}
