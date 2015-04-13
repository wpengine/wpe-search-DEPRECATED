<?php

$s['settings_page_title'] = __('ElasticPress For WPEngine');
$s['settings_menu_title'] = $s['settings_page_title'];
$s['settings_menu_slug'] = 'ep4wpe-plugin';
$s['settings_page_callback'] = 'ep4wpe_settings_page_callback';

$s['settings_group'] = 'ep4wpe-settings';

$s['show_related_post_setting_id'] = ep4wpe\SHOW_RELATED_POSTS_FIELD;
$s['show_related_post_setting_callback'] = 'ep4wpe_boolean_sanitize';

$s['related_posts_section_id'] = 'ep4wpe-related-posts-section';
$s['related_posts_section_title'] = __('Related Posts');
$s['related_posts_section_callback'] = 'ep4wpe_related_posts_section_callback';

$s['related_posts_do_show_field_id'] = 'ep4wpe-related-posts-do-show';
$s['related_posts_do_show_field_title'] = __('Show related posts below single posts');
$s['related_posts_do_show_field_callback'] = 'ep4wpe_related_posts_field_callback';


add_action( 'admin_menu', 'ep4wpe_custom_admin_menu' );
function ep4wpe_custom_admin_menu() {
  global $s;
  add_options_page(
                   $s['settings_page_title'],
                   $s['settings_menu_title'],
                   'manage_options',
                   $s['settings_menu_slug'],
                   $s['settings_page_callback']
                   );
}


add_action( 'admin_init', 'ep4wpe_settings_init' );
function ep4wpe_settings_init() {
  global $s;
  register_setting( $s['settings_group'], $s['show_related_post_setting_id'], $s['show_related_post_setting_callback'] );

  add_settings_section( $s['related_posts_section_id'], $s['related_posts_section_title'], $s['related_posts_section_callback'], $s['settings_menu_slug'] );

  add_settings_field( $s['related_posts_do_show_field_id'], $s['related_posts_do_show_field_title'], $s['related_posts_do_show_field_callback'],
                      $s['settings_menu_slug'], $s['related_posts_section_id'], [ 'id' => $s['show_related_post_setting_id'], 'default' => false ] ); 

}




/* Admin display callbacks */

function ep4wpe_settings_page_callback() {
  global $s;

  $state = ep4wpe\ep_is_activated() ? __('ACTIVE') : __('INACTIVE');
  $title = __('ElasticPress For WPEngine Settings');
  $statement = sprintf( __('ElasticPress is %s'), $state );

  echo "<div class=\"wrap\"><h2>$title</h2><p>$statement</p>";

  $stats_map = ep4wpe\ep_stats();
  if( isset( $stats_map ) ) {
    printf( "<div>Search index contains %s documents, utilizing %s of disk space", $stats_map['total']['docs']['count'], size_format( $stats_map['total']['store']['size_in_bytes'], 2 ) );
  }


  echo "<form method=\"post\" action=\"options.php\">";

  settings_fields( $s['settings_group'] );
  do_settings_sections( $s['settings_menu_slug'] );
  submit_button();

  echo "</form></div>";

}

function ep4wpe_related_posts_section_callback() {
}

function ep4wpe_related_posts_field_callback( $args ) {
  $name = $args['id'];
  $checked = checked( 1, get_option( $name ), false );
  echo <<<INPUT
<input name="$name" id="$name" type="checkbox" value="1" class="code" $checked />
INPUT;
}

function ep4wpe_boolean_sanitize( $input ) {
  return $input == true;
}




