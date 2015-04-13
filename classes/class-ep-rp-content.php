<?php
namespace ep4wpe;

class EP_Related_Posts_Content {

  /**
   * Return a singleton instance of the current class
   *
   * @since 0.9
   * @return object
   */
  public static function factory() {
    static $instance = false;

    if ( ! $instance ) {
      $instance = new self();
      add_action( 'init', array( $instance, 'setup' ) );
    }

    return $instance;
  }

  /**
   * Checks to see if we should be integrating and if so, sets up the appropriate actions and filters.
   * @since 0.9
   */
  public function setup() {
    $do_show_rp = get_site_option( namespace\SHOW_RELATED_POSTS_FIELD, false );

    if( ! $do_show_rp ) {
      return;
    }

    if ( ! ep_is_activated() ) {
      return;
    }
                
    add_filter( 'the_content', [ $this, 'filter_related_posts_after_content' ], 10, 1 );
  }

  public function filter_related_posts_after_content( $the_content ) {
    $related_posts_content = $this->get_related_posts_content();
    return $the_content . $related_posts_content;
  }

  private function get_related_posts_content() {
    $related_posts = ep_more_like_this( get_the_ID(), null );

    $rp_content = ' <hr class="above-related-posts" /><div class="related-posts-head">';
    $rp_content .= __( 'Related Posts', 'elasticpress' );
    $rp_content .= '</div>';
    if( $related_posts['found_posts'] > 0 ) {
      $rp_content .= '<ul class="ep4wpe-related-posts">';

      $post_limit = get_site_option( namespace\POST_COUNT_FIELD, namespace\MAX_RELATED_POSTS );

      for( $i = 0; $i < $post_limit; $i++ ) {
        $rp = $related_posts['posts'][$i];
        $rp_date = mysql2date('F j, Y', $rp['post_date']);
        $rp_content .= <<<"RELATEDPOST"
<li class="related-post-item">
  <a href="${rp['permalink']}" rel="bookmark" title="${rp['post_title']}" class="related-post-link" >${rp['post_title']}</a>
  <span class="related-post-date">${rp_date}</span>
</li>
RELATEDPOST;
      }
      
      $rp_content .= '</ul>';
    }
    else {
      $rp_content .= '<span class="no-related-posts">' . __( 'No related posts found.', 'elasticpress' ) . '</span>';
    }

    return $rp_content;
  }
}

EP_Related_Posts_Content::factory();

