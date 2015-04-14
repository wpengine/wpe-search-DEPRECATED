<?php
namespace ep4wpe;

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
    if( ! is_single() ) {
      return;
    }
    global $post;
    $post_id = $post->ID;
    $related_posts = ep_more_like_this( $post_id, null );

    echo $args['before_widget'];
    echo $args['before_title'] . __( 'Related posts', 'elasticpress' ) . $args['after_title'];
?>
<?php
   if( $related_posts['found_posts'] > 0 ) {
     echo '<ul class="ep4wpe-related-posts">';
     $count = 0;
     foreach( $related_posts['posts'] as $post ) {
?>
<li>
  <a href="<?php echo $post['permalink']; ?>" rel="bookmark" title="<?php echo $post['post_title']; ?>"><?php echo $post['post_title'] ?></a>
  <span class="post-date"><?php echo mysql2date('F j, Y', $post['post_date']);; ?></span>
</li>
<?php
       $count++;
       if( $count >= $instance[namespace\POST_COUNT_FIELD] ) {
         break;
       }
     }
     echo '</ul>';
   }
   else {
     echo '<span>' . __( 'No related posts found.', 'elasticpress' ) . '</span>';
   }
?>
<?php


    echo $args['after_widget'];
  }

  /**
   * Outputs the options form on admin
   *
   * @param array $instance The widget options
   */
  public function form( $instance ) {
    $post_count = ! empty( $instance[namespace\POST_COUNT_FIELD] ) ? $instance[namespace\POST_COUNT_FIELD] : 5;
?>
  <p>
     <label for="<?php echo $this->get_field_id( namespace\POST_COUNT_FIELD ); ?>"><?php printf( __( 'Post count (max %d):', 'elasticpress' ), namespace\MAX_RELATED_POSTS ); ?> </label> 
     <input class="widefat" id="<?php echo $this->get_field_id( namespace\POST_COUNT_FIELD ); ?>" name="<?php echo $this->get_field_name( namespace\POST_COUNT_FIELD ); ?>" type="text" value="<?php echo esc_attr( $post_count ); ?>">
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
    $instance[namespace\POST_COUNT_FIELD] = ( ! empty( $new_instance[namespace\POST_COUNT_FIELD] ) ) ? preg_replace( '/[^\d]/', '', $new_instance[namespace\POST_COUNT_FIELD] ) : '';
    
    /* Limit max value */
    if( $instance[namespace\POST_COUNT_FIELD] > namespace\MAX_RELATED_POSTS ) {
      $instance[namespace\POST_COUNT_FIELD] = namespace\MAX_RELATED_POSTS;
    }

    return $instance;
  }
}

add_action( 'widgets_init', function(){
    register_widget( 'ep4wpe\ElasticPress_Related_Posts_Widget' );
  });

