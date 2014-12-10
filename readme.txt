=== ElasticPressForWPEngine ===
Contributors: cgoldman@dhapdigital.com, WPEngine
Author URI: http://dhapdigital.com
Plugin URI: https://github.com/dhapdigitalinc/ElasticPressForWPEngine
Tags: search, elasticsearch, fuzzy, facet, searching, autosuggest, suggest, elastic, advanced search, wpengine
Requires at least: 3.7.1
Tested up to: 4.1
Stable tag: 1.2.1
License: MIT
License URI: http://opensource.org/licenses/MIT

Integrate Elasticsearch with WordPress, running hosted on WPEngine

== Description ==
ElasticPress is a WordPress-Elasticsearch integration that overrides default `WP_Query` behavior to give you search results from Elasticsearch instead of MySQL. The plugin is built to be managed entirely via the command line. ElasticPress supports cross-site search in multi-site WordPress installs.

Out of the box, WordPress search is rudimentary at best: Poor performance, inflexible and rigid matching algorithms, inability to search metadata and taxonomy information, no way to determine categories of your results, and most importantly overall poor result relevancy.

Elasticsearch is a search server based on [Lucene](http://lucene.apache.org/). It provides a distributed, multitenant-capable full-text search engine with a [REST](http://en.wikipedia.org/wiki/Representational_state_transfer)ful web interface and schema-free [JSON](http://json.org/) documents.

Coupling WordPress with Elasticsearch allows us to do amazing things with search including:

* Relevant results
* Autosuggest
* Fuzzy matching (catch misspellings as well as 'close' queries)
* Proximity and geographic queries
* Search metadata
* Search taxonomies
* Facets
* Search all sites on a multisite install
* [The list goes on...](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/search.html)

_Note:_ Requires [WP-CLI](http://wp-cli.org/) and [Elasticsearch](http://www.elasticsearch.org/).

Please refer to [Github](https://github.com/dhapdigitalinc/ElasticPressForWPEngine) for detailed usage instructions and documentation.

== Installation ==
1. First, you will need to properly [install and configure](http://www.elasticsearch.org/guide/en/elasticsearch/guide/current/_installing_elasticsearch.html) Elasticsearch.
2. Install [WP-CLI](http://wp-cli.org/).
3. Install the plugin in WordPress.

= Configuration =

First, make sure you have Elasticsearch configured properly and WP-CLI setup.

Before configuring the WordPress plugin, you need to decide how you want to run the plugin. The processes for
configuring single site and multi-site cross-site search are slightly different.

= Single Site =
1. Activate the plugin with WP-CLI. Remember to use the "--network" flag for multi-site installs: `wp plugin activate ElasticPressForWPEngine [--network]`
2. Configure the host of your Elasticsearch server: `wp ep4wpe set-host 192.168.50.4`
3. Do an initial sync (with mapping) with your ES server by running: `wp elasticpress index --setup [--network-wide]`.  Again, use the optional flag for multi-site installs.

== Changelog ==

= 1.2.1 =
* Elasticsearch host and port are configured using the Settings API.
* More-like-this API functionality is exposed via a Related Posts widget.

= 1.2 =
* Allow number of shards and replicas to be configurable.
* Improved searching algorithm. Favor exact matches over fuzzy matches.
* Query stack implementation to allow for query nesting.
* Filter and disable query integration on a per query basis.
* Support orderby` parameter in `WP_Query
* (Bug) We don't want to add the like_text query unless we have a non empty search string. This mimcs the behavior of MySQL or WP which will return everything if s is empty.
* (Bug) Change delete action to action_delete_post instead of action_trash_post
* (Bug) Remove _boost from mapping. _boost is deprecated by Elasticsearch.
* Improve unit testing for query ordering.

= 1.1 =
* Refactored `is_alive`, `is_activated`, and `is_activated_and_alive`. We now have functions `is_activated`, `elasticsearch_alive`, `index_exists`, and `is_activated`. This refactoring helped us fix #150.
* Add support for post_title and post_name orderby parameters in `WP_Query` integration. Add support for order parameters.

= 1.0 =
* Support `search_fields` parameter. Support author, title, excerpt, content, taxonomy, and meta within this parameter.
* Move all management functionality to WP-CLI commands
* Remove ES_Query and support everything through WP_Query
* Disable sync during import
* Check for valid blog ids in index names
* Improved bulk error handling
* No need for `ep_last_synced` meta
* No need for syncing taxonomy
* Improved unit test coverage
* `sites` WP_Query parameter to allow for search only on specific blogs

= 0.1.2 =
* Only index public taxonomies
* Support ES_Query parameter that designates post meta entries to be searched
* Escape post ID and site ID in API calls
* Fix escaping issues
* Additional tests
* Translation support
* Added is_alive function for checking health status of Elasticsearch server
* Renamed `statii` to `status`

= 0.1.0 =
* Initial plugin
