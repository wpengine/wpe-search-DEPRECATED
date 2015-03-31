ElasticPress For WPEngine
=========================

Integrate [Elasticsearch](http://www.elasticsearch.org/) with [WordPress](http://wordpress.org/) running hosted on [WPEngine](http://wpengine.com/).

## Background

Let's face it, WordPress search is rudimentary at best. Poor performance, inflexible and rigid matching algorithms (which means no comprehension of 'close' queries), the inability to search metadata and taxonomy information, no way to determine categories of your results and most importantly the overall relevancy of results is poor.

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

## Purpose

The goal of ElasticPress is to integrate WordPress with Elasticsearch. This plugin integrates with the [WP_Query](http://codex.wordpress.org/Class_Reference/WP_Query) object returning results from Elasticsearch instead of MySQL.

There are other Elasticsearch integration plugins available for WordPress. ElasticPress, unlike others, offers multi-site search. Elasticsearch is a complex topic and integration results in complex problems. Rather than providing a limited, clunky UI, we elected to instead provide full control via [WP-CLI](http://wp-cli.org/).

## Installation

1. First, you will need to properly [install and configure](http://www.elasticsearch.org/guide/en/elasticsearch/guide/current/_installing_elasticsearch.html) Elasticsearch.
2. ElasticPress requires WP-CLI. Install it by following [these instructions](http://wp-cli.org).
3. Install the plugin in WordPress. You can download a [zip via Github](https://github.com/dhapdigitalinc/ElasticPressForWPEngine/archive/master.zip) and upload it using the WP plugin uploader.

## Configuration

First, make sure you have Elasticsearch and WP-CLI configured properly.

You should be able to view the Elasticsearch server status in your browser before continuing.  Assuming the values below, the URL would be [http://192.168.50.4:9200/_status?pretty=true](http://192.168.50.4:9200/_status?pretty=true).

1. Activate the plugin with wp-cli.  Remember to use the ```--network``` flag for multi-site installs.

```php
wp plugin activate ElasticPressForWPEngine [--network]
```

2. Using wp-cli, configure the host of your Elasticsearch server. For example:

```php
wp ep4wpe set-host 192.168.50.4
```

3. _(Optional)_ Using wp-cli, configure the port of your Elasticsearch server. For example:

```php
wp ep4wpe set-port 9200
```

Port 9200 is used by default if not manually configured.

## Index Initialization


1. Using wp-cli, do an initial sync (with mapping) with your ES server by running the following commands.  Remember to use the ```--network-wide``` flag for multi-site installs.

```bash
wp ep4wpe index --setup [--network-wide]
```

Index names are automatically generated based on site URL.  Once your index or indices are initialized, ```WP_Query``` will be integrated with Elasticsearch and support a few special parameters.


## Usage

After running an index, ElasticPress integrates with `WP_Query` if and only if the query is a search or the `ep_integrate` parameter is passed (see below). The end goal is to support all the parameters available to `WP_Query` so the transition is completely transparent. Right now, our `WP_Query` integration supports *many* of the relevant `WP_Query` parameters and adds a couple special ones.

### Supported `WP_Query` Parameters

* ```s``` (*string*)

    Search keyword. By default used to search against ```post_title```, ```post_content```, and ```post_excerpt```.

* ```posts_per_page``` (*int*)

    Number of posts to show per page. Use -1 to show all posts (the ```offset``` parameter is ignored with a -1 value). Set the ```paged``` parameter to paginate based on ```posts_per_page```.

* ```tax_query``` (*array*)

    Filter posts by terms in taxonomies. Takes an array of form:

    ```php
    new WP_Query( array(
        's'         => 'search phrase',
        'tax_query' => array(
            array(
                'taxonomy' => 'taxonomy-name',
                'terms'    => array( ... ),
            ),
        ),
    ) );
    ```

    ```tax_query``` accepts an array of arrays where each inner array *only* supports ```taxonomy``` (string) and ```terms``` (string|array) parameters. ```terms``` is a slug, either in string or array form.

* ```meta_query``` (*array*)

    Filter posts by post meta conditions. Takes an array of form:

    ```php
    new WP_Query( array(
        's'          => 'search phrase',
        'meta_query' => array(
            array(
                'key'   => 'key_name',
                'value' => 'meta value',
                'compare' => '=',
            ),
        ),
    ) );
    ```

    ```meta_query``` accepts an array of arrays where each inner array *only* supports ```key``` (string), ```value``` (string|array|int), and ```compare``` (string) parameters. ```compare``` supports the following:

      * ```=``` - Posts will be returned that have a post meta key corresponding to ```key``` and a value that equals the value passed to ```value```.
      * ```!=``` - Posts will be returned that have a post meta key corresponding to ```key``` and a value that does NOT equal the value passed to ```value```.
      * ```EXISTS``` - Posts will be returned that have a post meta key corresponding to ```key```.
      * ```NOT EXISTS``` - Posts will be returned that do not have a post meta key corresponding to ```key```.

    The outer array also supports a ```relation``` (string) parameter. By default ```relation``` is set to ```AND```:
    ```php
    new WP_Query( array(
        's'          => 'search phrase',
        'meta_query' => array(
            array(
                'key'   => 'key_name',
                'value' => 'meta value',
                'compare' => '=',
            ),
            array(
                'key'   => 'key_name2',
                'value' => 'meta value',
                'compare' => '!=',
            ),
            'relation' => 'AND',
        ),
    ) );
    ```

    Possible values for ```relation``` are ```OR``` and ```AND```. If ```relation``` is set to ```AND```, all inner queries must be true for a post to be returned. If ```relation``` is set to ```OR```, only one of the inner meta queries must be true for the post to be returned.

* ```post_type``` (*string*/*array*)

    Filter posts by post type. ```any``` wil search all public post types. `WP_Query` defaults to either `post` or `any` if no `post_type` is provided depending on the context of the query. This is confusing. ElasticPress will ALWAYS default to `any` if no `post_type` is provided. If you want to search for `post` posts, you MUST specify `post` as the `post_type`.

* ```offset``` (*int*)

    Number of posts to skip in ascending order.

* ```paged``` (*int*)

    Page number of posts to be used with ```posts_per_page```.

* ```author``` (*int*)

    Show posts associated with certain author ID.
    
* ```author_name``` (*string*)

    Show posts associated with certain author. Use ```user_nicename``` (NOT name).
    
* ```orderby``` (*string*)

    Order results by field name instead of relevance. Currently only supports: ```title```, ```name```, and ```relevance``` (default).

* ```order``` (*string*)

    Which direction to order results in. Accepts ```ASC``` and ```DESC```. Default is ```DESC```.

The following are special parameters that are only supported by ElasticPress.

* ```search_fields``` (*array*)

    If not specified, defaults to ```array( 'post_title', 'post_excerpt', 'post_content' )```.

    * ```post_title``` (*string*)

        Applies current search to post titles.

    * ```post_content``` (*string*)

        Applies current search to post content.

    * ```post_excerpt``` (*string*)

        Applies current search to post excerpts.

    * ```taxonomies``` (*string* => *array*/*string*)

        Applies the current search to terms within a taxonomy or taxonomies. The following will fuzzy search across ```post_title```, ```post_excerpt```, ```post_content```, and terms within taxonomies ```category``` and ```post_tag```:

        ```php
        new WP_Query( array(
            's'             => 'term search phrase',
            'search_fields' => array(
                'post_title',
                'post_content',
                'post_excerpt',
                'taxonomies' => array( 'category', 'post_tag' ),
            ),
        ) );
        ```

    * ```meta``` (*string* => *array*/*string*)

        Applies the current search to post meta. The following will fuzzy search across ```post_title```, ```post_excerpt```, ```post_content```, and post meta keys ```meta_key_1``` and ```meta_key_2```:

        ```php
        new WP_Query( array(
            's'             => 'meta search phrase',
            'search_fields' => array(
                'post_title',
                'post_content',
                'post_excerpt',
                'meta' => array( 'meta_key_1', 'meta_key_2' ),
            ),
        ) );
        ```

    * ```author_name``` (*string*)

        Applies the current search to author login names. The following will fuzzy search across ```post_title```, ```post_excerpt```, ```post_content``` and author ```user_login```:

        ```php
        new WP_Query( array(
            's'             => 'username',
            'search_fields' => array(
                'post_title',
                'post_content',
                'post_excerpt',
                'author_name',
            ),
        ) );
        ```

* ```aggs``` (*array*)

    Add aggregation results to your search result. For example:
    
    ```php
    new WP_Query( array(
        's'    => 'search phrase',
        'aggs' => array(
            'name'       => 'name-of-aggregation', // (can be whatever you'd like)
            'use-filter' => true // (*bool*) used if you'd like to apply the other filters (i.e. post type, tax_query, author), to the aggregation
            'aggs'       => array(
                'name'  => 'name-of-sub-aggregation',
                'terms' => array(
                    'field' => 'terms.name-of-taxonomy.name-of-term',
                ),
            ),
        ),
    ) );
    ```

* ```sites``` (*int*/*string*/*array*)

    This parameter only applies in a multi-site environment. It lets you search for posts on specific sites or across the network.

    By default, ```sites``` defaults to ```current``` which searches the current site on the network:

    ```php
    new WP_Query( array(
        's'     => 'search phrase',
        'sites' => 'current',
    ) );
    ```

    You can search on all sites across the network:

    ```php
    new WP_Query( array(
        's'     => 'search phrase',
        'sites' => 'all',
    ) );
    ```

    You can also specify specific sites by id on the network:

    ```php
    new WP_Query( array(
        's'     => 'search phrase',
        'sites' => 3,
    ) );
    ```

    You can even specify a group of specific sites on the network:
    ```php
    new WP_Query( array(
        's'     => 'search phrase',
        'sites' => array( 2, 3 ),
    ) );
    ```

    _Note:_ Nesting cross-site `WP_Query` loops can result in unexpected behavior.

* ```ep_integrate``` (*bool*)

    Allows you to perform queries without passing a search parameter. This is pretty powerful as you can leverage Elasticsearch to retrieve queries that are too complex for MySQL (such as a 5-dimensional taxonomy query). For example:
    
    Get 20 of the lastest posts
    ```php
    new WP_Query( array(
        'ep_integrate'   => true,
        'post_type'      => 'post',
        'posts_per_page' => 20,
    ) );
    ```
    
    Get all posts with a specific category
    ```php
    new WP_Query( array(
        'ep_integrate'   => true,
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'category'       => 5,
    ) );
    ```

## Related Posts Widget

The plugin makes available a widget called **ElasticPress Related Posts**, with one configurable option: the number of posts to display in the widget, up to a maximum of 10.

Content is only generated if the widget is on a single post page, and uses the post ID to call Elasticsearch using its "[more like this API](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/search-more-like-this.html)".

The widget generates relatively generic HTML that may be styled to match any theme.  This is an example of the output with the option set to only one post.

```html
<ul class="ep4wpe-related-posts">
<li>
  <a href="http://dhap.wpengine.com/parent-child-relationship/" rel="bookmark" title="Parent / Child Relationship">Parent / Child Relationship</a>
  <span class="post-date">June 30, 2013</span>
</li>
</ul>
```

### Supported WP-CLI Commands

The following commands are supported by ElasticPress:

* `wp ep4wpe index [--setup] [--network-wide] [--posts-per-page] [--no-bulk]`

  Index all posts in the current blog. `--network-wide` will force indexing on all the blogs in the network. `--setup` will clear the index first and re-send the put mapping. `--posts-per-page` let's you determine the amount of posts to be indexed per bulk index (or cycle). `--no-bulk` let's you disable bulk indexing.

* `wp ep4wpe activate`

  Turns on ElasticPress integration. Integration is automatically deactivated during indexing.

* `wp ep4wpe deactivate`

  Turns off ElasticPress integration. Integration is automatically deactivated during indexing.

* `wp ep4wpe delete-index [--network-wide]`

  Deletes the current blog index. `--network-wide` will force every index on the network to be deleted.

* `wp ep4wpe is-active`

  Checks whether ElasticPress is currently integration active. This is different than whether the plugin is WordPress active or not. During indexing, integration will be deactivated automatically.

* `wp ep4wpe put-mapping [--network-wide]`

  Sends plugin put mapping to the current blog index. `--network-wide` will force mappings to be sent for every index in the network.

* `wp ep4wpe recreate-network-alias`

  Recreates the alias index which points to every index in the network.

* `wp ep4wpe stats`

  Returns basic stats on Elasticsearch instance i.e. number of documents in current index as well as disk space used.

* `wp ep4wpe status`


## Development

### Setup

Follow the configuration instructions above to setup the plugin.

### Testing

Within the terminal change directories to the plugin folder. Initialize your testing environment by running the
following command:

For VVV users:
```
bash bin/install-wp-tests.sh wordpress_test root root localhost latest
```

For VIP Quickstart users:
```
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
```

where:

* ```wordpress_test``` is the name of the test database (all data will be deleted!)
* ```root``` is the MySQL user name
* ```root``` is the MySQL user password (if you're running VVV). Blank if you're running VIP Quickstart.
* ```localhost``` is the MySQL server host
* ```latest``` is the WordPress version; could also be 3.7, 3.6.2 etc.


Our test suite depends on a running Elasticsearch server. You can supply a host to PHPUnit as an environmental variable like so:

```bash
EP_HOST="http://192.168.50.4:9200" phpunit
```

#### Dockunit

ElasticPress contains a valid [Dockunit](https://www.npmjs.com/package/dockunit) file for running unit tests across a variety of environments locally (PHP 5.2 and 5.5). It assumes the address of your Elasticsearch server is `http://192.168.50.4:9200`. You can use Dockunit by running:

```bash
dockunit
```

### Issues

If you identify any errors or have an idea for improving the plugin, please [open an issue](https://github.com/10up/ElasticPress/issues?state=open). We're excited to see what the community thinks of this project, and we would love your input!


## License

ElasticPress is free software; you can redistribute it and/or modify it under the terms of the [GNU General Public License](http://www.gnu.org/licenses/gpl-2.0.html) as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
