<?php

/**
 * Plugin Name: ElasticPress For WP Engine
 * Description: Integrate WPEngine WordPress search with Elasticsearch
 * Version:     1.1
 * Author:      Aaron Holbrook, Taylor Lovett, Matt Gross, 10up, Chris Goldman
 * Author URI:  http://10up.com, http://dhapdigital.com
 * License:     MIT
 *
 * This program derives work from Alley Interactive's SearchPress
 * and Automattic's VIP search plugin:
 *
 * Copyright (C) 2012-2013 Automattic
 * Copyright (C) 2013 SearchPress
 * Copyright (c) 2014 WPEngine
 */

require_once( 'classes/class-ep-config.php' );
require_once( 'classes/class-ep-api.php' );
require_once( 'classes/class-ep-sync-manager.php' );
require_once( 'classes/class-ep-elasticpress.php' );
require_once( 'classes/class-ep-wp-query-integration.php' );

/**
 * WP CLI Commands
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once 'bin/wp-cli.php';
}