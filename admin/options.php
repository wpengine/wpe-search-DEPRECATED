<?php

/* Admin display callbacks */

function ep4wpe_settings_page() {
?>
  <div class="wrap">
    <h2>ElasticPress For WPEngine Settings</h2>

    [<?php get_site_option( "ep4wpe\\is_active_option" ); ?>]

    <form method="post" action="options.php">
    <?php settings_fields( 'ep4wpe_elasticsearch_server' ); ?>
    <?php do_settings_sections( 'ep4wpe_settings_page' ); ?>
    </form>

  </div>
<?php
}

function ep4wpe_elasticsearch_server_section() {
  echo "<em>All values are read-only.</em>";
}

function ep4wpe_print_field_callback( $args ) {
  $field_id = $args{'id'};
  $default = isset( $args{'default'} ) ? $args{'default'} : '';
  $value = get_option( $field_id, $default );
  printf( '<input type="text" id="%s" name="ep4wpe_settings[%s]" value="%s" disabled="disabled" >', $field_id, $field_id, $value );
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
                     array( 'id' => 'ep4wpe_elasticsearch_server_host' )
                     );

  add_settings_field(
                     'ep4wpe_elasticsearch_server_port',
                     'ES port number',
                     'ep4wpe_print_field_callback',
                     'ep4wpe_settings_page',
                     'ep4wpe_elasticsearch_server',
                     array( 'id' => 'ep4wpe_elasticsearch_server_port', 'default' => '9200' )
                     );


}


/* Add actions, set callbacks */

add_action( 'admin_menu', 'ep4wpe_custom_admin_menu' );
 
add_action( 'admin_init', 'ep4wpe_settings_init' );

