<?php
namespace ep4wpe;

define( __NAMESPACE__ . '\MAX_RELATED_POSTS', 10 );

class ElasticPress_Related_Posts_Widget extends \WP_Widget {

  /**
   * Sets up the widgets name etc
   */
  public function __construct() {
    parent::__construct(
                        'ep4wpe_related_posts_widget', // Base ID
                        __( 'ElasticPress Related Posts', 'elasticpress' ), // Name
                        array( 'description' => __( 'Related posts driven by "more like this" functionality from Elasticsearch.', 'elasticpress' ), ) // Args
                        );
  }

  /**
   * Outputs the content of the widget
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args, $instance ) {
    $post_id = $GLOBALS['post']->ID;
    $related_posts = ep_more_like_this( $post_id, null );

    echo $args['before_widget'];
    echo $args['before_title'] . __( 'Related posts', 'elasticpress' ) . $args['after_title'];
?>
<section id="ep4wpe-related-posts-section" clas="clearfix">
<?php
   if( $related_posts['found_posts'] > 0 ) {
     echo '<ul class="ep4wpe-related-posts">';
     foreach( $related_posts['posts'] as $post ) {
?>
<li><h4 class="entry-title"><a href="<?php echo $post['permalink']; ?>" rel="bookmark" title="<?php echo $post['post_title']; ?>"><img src="<?php echo includes_url(); ?>images/crystal/document.png" width="14px"><?php echo $post['post_title'] ?></a></h4></li>
<?php
     }
     echo '</ul>';
   }
   else {
     echo '<span>' . __( 'No related posts found.', 'elasticpress' ) . '</span>';
   }
?>
</ul>
</section>
<?php


    echo $args['after_widget'];
  }

  /**
   * Outputs the options form on admin
   *
   * @param array $instance The widget options
   */
  public function form( $instance ) {
    $post_count = ! empty( $instance['ep4wpe_count'] ) ? $instance['ep4wpe_count'] : __( '5', 'elasticpress' );
?>
  <p>
     <label for="<?php echo $this->get_field_id( 'ep4wpe_count' ); ?>"><?php _e( 'Post count:' ); ?></label> 
     <input class="widefat" id="<?php echo $this->get_field_id( 'ep4wpe_count' ); ?>" name="<?php echo $this->get_field_name( 'ep4wpe_count' ); ?>" type="text" value="<?php echo esc_attr( $post_count ); ?>">
  </p>
<?php 
}

  /**
   * Processing widget options on save
   *
   * @param array $new_instance The new options
   * @param array $old_instance The previous options
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();

    /* Allow only digits */
    $instance['ep4wpe_count'] = ( ! empty( $new_instance['ep4wpe_count'] ) ) ? preg_replace( '/[^\d]/', '', $new_instance['ep4wpe_count'] ) : '';
    
    /* Limit max value */
    echo namespace\MAX_RELATED_POSTS;
    if( $instance['ep4wpe_count'] > namespace\MAX_RELATED_POSTS ) {
      $instance['ep4wpe_count'] = namespace\MAX_RELATED_POSTS;
    }

    return $instance;
  }
}

add_action( 'widgets_init', function(){
    register_widget( 'ep4wpe\ElasticPress_Related_Posts_Widget' );
  });

