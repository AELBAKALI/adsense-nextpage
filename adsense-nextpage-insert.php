<?php
/*
 * Plugin Name: AdSense NextPage Insert
 * Description: Inserts AdSense code before and after the <!--nextpage--> tag in a post.
 * Version: 1.0
 * Author: ABDELFATAH EL BAKALI
 * License: GPL2
 */

function adSenseNextPageInsert($post_content) {
  if (is_single() && strpos($post_content, '<!--nextpage-->') !== false) {
    // Insert AdSense code before <!--nextpage-->
    $post_content = str_replace('<!--nextpage-->', '<!--nextpage--><div class="adsense-code">Your AdSense code goes here</div>', $post_content);
    // Insert AdSense code after <!--nextpage-->
    $post_content = str_replace('<!--nextpage-->', '<div class="adsense-code">Your AdSense code goes here</div><!--nextpage-->', $post_content);
  }
  return $post_content;
}
add_filter('the_content', 'adSenseNextPageInsert');

function adSenseNextPageInsert_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=adsense-nextpage-insert">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'adSenseNextPageInsert_settings_link');

function adSenseNextPageInsert_settings_page() {
  add_options_page(
    'AdSense NextPage Insert Settings',
    'AdSense NextPage Insert',
    'manage_options',
    'adsense-nextpage-insert',
    'adSenseNextPageInsert_settings_page_html'
  );
}
add_action('admin_menu', 'adSenseNextPageInsert_settings_page');

function adSenseNextPageInsert_settings_page_html() {
  if (!current_user_can('manage_options')) {
    return;
  }
  ?>
  <div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
      <?php
      settings_fields('adSenseNextPageInsert_options');
      do_settings_sections('adsense-nextpage-insert');
      submit_button('Save Settings');
      ?>
    </form>
  </div>
  <?php
}

function adSenseNextPageInsert_settings_page_init() {
  register_setting(
    'adSenseNextPageInsert_options',
    'adSenseNextPageInsert_options',
    'adSenseNextPageInsert_options_validate'
  );
  add_settings_section(
    'adSenseNextPageInsert_section_developers',
    'AdSense Code',
    'adSenseNextPageInsert_section_developers_cb',
    'adsense-nextpage-insert'
  );
 add_settings_field(
'adSenseNextPageInsert_field_input',
'AdSense Code',
'adSenseNextPageInsert_field_input_cb',
'adsense-nextpage-insert',
'adSenseNextPageInsert_section_developers'
);
}
add_action('admin_init', 'adSenseNextPageInsert_settings_page_init');

