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
  $stats_map = ep4wpe\ep_stats();
  if( isset( $stats_map ) ) {
?>
    <?php printf( "<div>Search index contains %s documents, utilizing %s of disk space", $stats_map['total']['docs']['count'], size_format( $stats_map['total']['store']['size_in_bytes'], 2 ) ); ?> 

<?php
                                                                                         }
}

function ep4wpe_print_field_callback( $args ) {
  $field_id = $args{'id'};
  $default = isset( $args{'default'} ) ? $args{'default'} : '';
  echo get_site_option( $field_id, $default );
}

function ep4wpe_memcached_server_section() {
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
                     array( 'id' => 'ep4wpe_host', 'default' => 'localhost' )
                     );

  add_settings_field(
                     'ep4wpe_elasticsearch_server_port',
                     'ES port number',
                     'ep4wpe_print_field_callback',
                     'ep4wpe_settings_page',
                     'ep4wpe_elasticsearch_server',
                     array( 'id' => 'ep4wpe_port', 'default' => '9200' )
                     );

  add_settings_section(
                       'ep4wpe_memcached_server',
                       'Memcached server',
                       'ep4wpe_memcached_server_section',
                       'ep4wpe_settings_page'
                       );

  add_settings_field(
                     'ep4wpe_memcached_server_host',
                     'memcached host (name or address)',
                     'ep4wpe_print_field_callback',
                     'ep4wpe_settings_page',
                     'ep4wpe_memcached_server',
                     array( 'id' => 'ep4wpe_memcached_host', 'default' => 'localhost' )
                     );

  add_settings_field(
                     'ep4wpe_memcached_server_port',
                     'memcached port number',
                     'ep4wpe_print_field_callback',
                     'ep4wpe_settings_page',
                     'ep4wpe_memcached_server',
                     array( 'id' => 'ep4wpe_memcached_port', 'default' => '11211' )
                     );

}


/* Add actions, set callbacks */

add_action( 'admin_menu', 'ep4wpe_custom_admin_menu' );
 
add_action( 'admin_init', 'ep4wpe_settings_init' );

