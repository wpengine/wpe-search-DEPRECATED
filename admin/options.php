<?php

/* Admin display callbacks */

function ep4wpe_settings_page() {
?>
  <div class="wrap">
    <h2>ElasticPress For WPEngine Settings</h2>

    <p>ElasticPress is <?php echo ep4wpe\ep_is_activated() ? 'ACTIVE' : 'INACTIVE'; ?>.<p>

    <?php settings_fields( 'ep4wpe_elasticsearch_server' ); ?>
    <?php do_settings_sections( 'ep4wpe_settings_page' ); ?>


  </div>
<?php
}

function ep4wpe_elasticsearch_server_section() {

  if( array_key_exists( 'ep_index', $_GET ) && 'true' === $_GET['ep_index'] ) {
    ep4wpe\ep_index_all();
    sleep( 1 );
  }

  $stats_map = ep4wpe\ep_stats();
  $index_button_label = 'Index site';
  if( isset( $stats_map ) ) {
    $index_button_label = 'Re-index site';
?>
    <?php printf( "<div>Search index contains %s documents, utilizing %s of disk space", $stats_map['total']['docs']['count'], size_format( $stats_map['total']['store']['size_in_bytes'], 2 ) ); ?> 
<?php
  }
?>
  <div><a href="<?php echo admin_url( 'options-general.php?page=ep4wpe-plugin&ep_index=true' ); ?>" class="button secondary-button" onclick="javascript:">Re-index site</a></div>

<?php
}

function ep4wpe_print_field_callback( $args ) {
  $field_id = $args{'id'};
  $default = isset( $args{'default'} ) ? $args{'default'} : '';
  echo get_site_option( $field_id, $default );
}

/* Admin action callbacks */

function ep4wpe_custom_admin_menu() {
  add_options_page(
                   'ElasticPress For WPEngine',
                   'ElasticPress For WPEngine',
                   'manage_options',
                   'ep4wpe-plugin',
                   'ep4wpe_settings_page'
                   );
}

function ep4wpe_settings_init() {

  register_setting( 'ep4wpe_elasticsearch_server', 'ep4wpe_settings' );

  add_settings_section(
                       'ep4wpe_elasticsearch_server',
                       'Elasticsearch server',
                       'ep4wpe_elasticsearch_server_section',
                       'ep4wpe_settings_page'
                       );

  add_settings_field(
                     'ep4wpe_elasticsearch_server_host',
                     'ES host (name or address)',
                     'ep4wpe_print_field_callback',
                     'ep4wpe_settings_page',
                     'ep4wpe_elasticsearch_server',
                     array( 'id' => 'ep4wpe_host' )
                     );

  add_settings_field(
                     'ep4wpe_elasticsearch_server_port',
                     'ES port number',
                     'ep4wpe_print_field_callback',
                     'ep4wpe_settings_page',
                     'ep4wpe_elasticsearch_server',
                     array( 'id' => 'ep4wpe_port', 'default' => '9200' )
                     );

}

/* Add actions, set callbacks */

add_action( 'admin_menu', 'ep4wpe_custom_admin_menu' );
 
add_action( 'admin_init', 'ep4wpe_settings_init' );
