=== WP Engine Search ===
Contributors: cgoldman@dhapdigital.com, WPEngine
Author URI: http://dhapdigital.com
Plugin URI: https://github.com/wpengine/wpe-search/
Tags: search, elasticsearch, fuzzy, facet, searching, autosuggest, suggest, elastic, advanced search, wpengine
Requires at least: 3.7.1
Tested up to: 4.2.1
Stable tag: 1.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrate Elasticsearch with WordPress for users of the WP Engine platform.

== Description ==
WP Engine Search is a WordPress & Elasticsearch integration that overrides default `WP_Query` behavior to give you search results from an Elasticsearch instance rather than MySQL. While there is a basic settings page, the plugin is built to be managed entirely via the command line.

Let's face it, WordPress search is rudimentary at best. Lackluster performance, inflexible and rigid matching algorithms (which means no comprehension of 'close' queries), the inability to search metadata and taxonomy information, no way to determine categories of your results and most importantly the overall relevancy of results is poor.

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

Please refer to [GitHub](https://github.com/wpengine/wpe-search/) for detailed usage instructions and documentation.

== Installation ==
1. Download the [current development release](https://github.com/wpengine/wpe-search/archive/master.zip) and install it using the WordPress plugin uploader GUI.
2. Activate the plugin.

= Configuration =

_Important!_ Members of the WP Engine Search alpha *do not* need to follow these steps! The plugin will be configured for you by our Labs team. However, if you have an Elasticsearch instance you would like to use, feel free to follow the steps below.

1. Activate the plugin with `wp-cli`. Remember to use the `--network` flag for Multisite installs: `wp plugin activate wpe-search [--network]`

2. Using `wp-cli`, configure the host of your Elasticsearch server: `wp ep4wpe set-host 192.168.50.4`

3. _(Optional)_ If your instance of Elasticsearch is running on a different port, use `wp-cli` to set the port number: `wp ep4wpe set-port 9200`

_Note:_ Port 9200 is used by default if not manually configured.

== Changelog ==

= 1.5.0 =
* Forked plugin from [ElasticPress](https://github.com/10up/ElasticPress).
* Add option to display related posts below single posts.
* Add UI to kick-off content reindexing.
* Add code to leverage WP Engine EverCache to cache search results.

= 1.3.1 =
* Support `date` in WP_Query `orderby`.

= 1.3 =
* Support `meta_query` in WP_Query integration
* Improved documentation. Each WP-CLI command has been documented
* Add `elasticsearch` property to global post object to assist in debugging
* `ep_integrate` param added to allow for WP_Query integration without search. (Formally called ep_match_all)
* Filter added for post statuses (defaults to `publish`). Change the sync mechanism to make sure it takes all post statuses into account. Props [jonathanbardo](https://github.com/jonathanbardo)
* Bug fix: check if failed post exists in indexing. Props [elliot-stocks](https://github.com/elliott-stocks)
* Bug fix: properly check if setup is defined in indexing. Props [elliot-stocks](https://github.com/elliott-stocks)
* Bug fix: add WP_Query integration on init rather than plugins loaded. Props [adamsilverstein](https://github.com/adamsilverstein)
* Bug fix: Properly set global post object post type in loop. Props [tott](https://github.com/tott)
* Bug fix: Do not check if index exists on every page load. Refactor so we can revert to MySQL after failed ES ping.
* Bug fix: Make sure we check `is_multisite()` if `--network-wide` is provided. Props [ivankruchkoff](https://github.com/ivankruchkoff)
* Bug fix: Abide by the `exclude_from_search` flag from post type when running search queries. Props [ryanboswell](https://github.com/ryanboswell)
* Bug fix: Correct mapping of `post_status` to `not_analyzed` to allow for filtering of the search query (will require a re-index). Props [jonathanbardo](https://github.com/jonathanbardo)

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
