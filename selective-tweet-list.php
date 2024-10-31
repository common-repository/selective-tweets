<?php
/*
Plugin Name: Selective tweet list
Plugin URI: http://wordpress.org/extend/plugins/selective-tweets/
Description: Widget shows list of tweets from specified user which are marked by a specified keyword.
Version: 0.3
Author: publicvoid
Author URI: http://wordpress.org/extend/plugins/profile/publicvoid
*/

add_action('widgets_init', create_function('', 'return register_widget("Selective_Status_Widget");'));

class Selective_Status_Widget extends WP_Widget {

	function Selective_Status_Widget() {
		$widget_ops = array(
      'description' => 'Shows statuses from specified user which are marked by a special keyword.'
    );
    $this->WP_Widget( 'selective-status', 'Selective Status', $widget_ops);
	}

	function form($instance) {
    // show the options form
		$defaults = array(
      'twitter_name' => 'myname',
      'selection_marker' => '#wp',
      'tweets_count' => 7,
      'list_title' => 'Selective tweet status', 
      'noscript_hint' => 'This list can only be shown with javascript switched on.',
      'twitter_error' => 'Error in status api, list could not be fetched.',
      'status_net' => false
    );
		$instance = wp_parse_args( (array) $instance, $defaults );
    $texts = array(
      "twitter_name"=> array(
        "title"=>"user name",
        "hint"=>"micro blogging user name to be used to fetch the statuses"),
      "selection_marker"=> array(
        "title"=>"selection marker",
        "hint"=>"used to mark tweets that should be included into the list"),
      "tweets_count" => array(
        "title"=>"tweets count",
        "hint"=>"number of tweets to be fetched from twitter (max. 100)"),
      "list_title" =>  array(
        "title"=>"list title",
        "hint"=>"header of the selective tweets list"),
      "noscript_hint" => array(
        "title"=>"noscript hint",
        "hint"=>"This hint will be shown for users with javascript switched off."),
      "twitter_error"=> array(
        "title"=>"twitter error message",
        "hint"=>"This message will be shown, when twitter is not reachable and fetching data leeds to an error")
    ); 
    $field_name = $this->get_field_name("status_net");
    $field_id = $this->get_field_id("status_net");
    ?>
    <p>
      <label title="which microblogging service you want to be queried">microblogging service:</label><br/>
      <input id="<?php echo $field_id; ?>1" type="radio" name="<?php echo $field_name; ?>" value="twitter" <?php checked( $instance['status_net'], false ); ?>/> Twitter
      <input id="<?php echo $field_id; ?>2" type="radio" name="<?php echo $field_name; ?>" value="status" <?php checked( $instance['status_net'], true ); ?> /> Identi.ca
    </p>
    <?php 
    foreach ($texts as $key => $texts) {
      $field_id = $this->get_field_id($key);
      $field_name = $this->get_field_name($key); ?>
      <p>
	  		<label for="<?php echo $field_id; ?>" title="<?php echo $texts['hint']; ?>" ><?php echo $texts['title']; ?>:</label>
	  		<input id="<?php echo $field_id; ?>" name="<?php echo $field_name; ?>" value="<?php echo $instance[$key]; ?>" style="width:100%;" />
      </p>
    <?php
    }
	}

	function update($new_instance, $old_instance) {
    // update settings from options
    $instance = $old_instance;
    foreach (array('twitter_name','selection_marker','list_title','noscript_hint','twitter_error') as $field) {
      $instance[$field] = strip_tags($new_instance[$field]);
    }
    $instance['status_net'] = ($new_instance['status_net']=="status");
    $instance['tweets_count'] = min( intval($new_instance['tweets_count']), 100 );
    // 100 is the max results per page (rpp) value for twitters search api and should definitly be enough (so we need to only fetch one page)
		return $instance;
  }

	function widget($args, $instance) {
    // actually displays the widget
    extract($args);
    $list_title = apply_filters('widget_title', $instance['list_title'] );
    echo $before_widget;
    echo $before_title . $list_title . $after_title; ?>
    <ul id="tweetlist">
      <noscript><li><?php echo $instance['noscript_hint']; ?></li></noscript>
    </ul>
    <!-- // TODO ? wp_enqueue_script, don't load multiple times +++ use a proper library for status api requests -->
    <script src= "<?php echo plugins_url('selective-tweets/selective-tweet-list.js', 'selective-tweets'); ?>"></script>
    <!-- urls: see http://codex.wordpress.org/Determining_Plugin_and_Content_Directories -->
    <script>selectiveTweetList.init({
      "marker": "<?php echo esc_js($instance['selection_marker']); ?>",
      "twitterName": "<?php echo esc_js($instance['twitter_name']); ?>",
      "count": <?php echo $instance['tweets_count']; ?>,
      "errorText" : "<?php echo esc_js($instance['twitter_error']); ?>",
      "listId": "tweetlist",
      "statusNet": <?php echo $instance['status_net'] ? 'true' : 'false'; ?>
    });</script>
    <?php echo $after_widget;    
	}
}

?>
