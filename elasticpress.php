<?php

/**
 * Plugin Name: ElasticPress For WP Engine
 * Description: Integrate WPEngine WordPress search with Elasticsearch
 * Version:     1.1.1
 * Author:      Christopher Goldman, DHAP Digital, Inc.
 * Author URI:  http://dhapdigital.com
 * License:     MIT
 *
 * This program derives work from Alley Interactive's SearchPress
 * and Automattic's VIP search plugin:
 *
 * Copyright (C) 2012-2013 Automattic
 * Copyright (C) 2013 SearchPress
 * Copyright (c) 2014 WPEngine
 */

define( 'EP4WPE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); // Has trailing slash

require_once( EP4WPE__PLUGIN_DIR . 'classes/class-ep-config.php' );
require_once( EP4WPE__PLUGIN_DIR . 'classes/class-ep-api.php' );
require_once( EP4WPE__PLUGIN_DIR . 'classes/class-ep-sync-manager.php' );
require_once( EP4WPE__PLUGIN_DIR . 'classes/class-ep-elasticpress.php' );
require_once( EP4WPE__PLUGIN_DIR . 'classes/class-ep-wp-query-integration.php' );

require_once( EP4WPE__PLUGIN_DIR . 'admin/options.php' );

/**
 * WP CLI Commands
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
  require_once( EP4WPE__PLUGIN_DIR . 'bin/wp-cli.php' );
}