<?php
/*
Plugin Name: Count User Comments
Plugin URI: 
Description: Counts the comments made by a registered user. They have to be signed in while making the comment to have it add to the total.
Version: 1.0
Author: WP Theme Tutorial, Curis McHale
Author URI: http://wpthemetutorial.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * All the config stuff so we can update our plugin from GitHub
 *
 * @since   1.1
 * @author  WP Theme Tutorial, Curtis McHale
 */
function theme_t_count_users_wp_github_update() {

	include_once( plugin_dir_path( __FILE__ ) . '/plugin-updater/updater.php' );

	define('WP_GITHUB_FORCE_UPDATE', true);

	if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin

		$config = array(
			'slug' => plugin_basename(__FILE__),
			'proper_folder_name' => 'wp-theme-t-count-user-comments',
			'api_url' => 'https://api.github.com/repos/curtismchale/Count-Comments-by-User---WP-Theme-Tutorial',
			'raw_url' => 'https://raw.github.com/curtismchale/Count-Comments-by-User---WP-Theme-Tutorial/master',
			'github_url' => 'https://github.com/curtismchale/Count-Comments-by-User---WP-Theme-Tutorial',
			'zip_url' => 'https://github.com/curtismchale/Count-Comments-by-User---WP-Theme-Tutorial/zipball/master',
			'sslverify' => true,
			'requires' => '3.4',
			'tested' => '3.4',
			'readme' => 'readme.txt'
		);

		new WPGitHubUpdater($config);

	}

}
add_action( 'init', 'theme_t_count_users_wp_github_update' );

/**
 * Number of comments user has written. This is a modified version of the count_user_posts
 * function from WordPress core.
 *
 * @param   int   $userid   User ID.
 * @return  int   $count    Amount of comments user has written.
 *
 * @since   1.0
 * @author  WP Theme Tutorial, Curtis McHale
 */
function theme_t_wp_count_user_comments( $userid ) {

        global $wpdb;

        $count = $wpdb->get_var(
          'SELECT COUNT( comment_id ) FROM '. $wpdb->comments .'
          WHERE user_id = '.$userid.'
          AND comment_approved = "1"
          AND comment_type NOT IN ("pingback", "trackback" )'
        );

        // handle no comments by user
        if( $count === '0' ) $count = apply_filters( 'theme_t_wp_no_user_comments', 'No Comments by User' );

        return apply_filters('theme_t_wp_get_user_comment_count', $count, $userid);

}

/**
 * Adds the admin column header which will contain the comment count per user
 *
 * @param   array   $columns  req   The current columns
 * @return  array   $columns        The columns with our additions
 *
 * @since   1.0
 * @author  WP Theme Tutorial, Curtis McHale
 */
function theme_t_wp_add_comment_admin_column( $columns ){

  return array_merge( $columns,
    array( 'comment_count_by_user' => "Comment Count" )
  );

}
add_filter( 'manage_users_columns', 'theme_t_wp_add_comment_admin_column' );

/**
 * Provides the content to our custom admin column
 *
 * @param 	string 	$custom_column 	  req 	The name of the custom column
 * @param 	string 	$column_name 	    req 	The name of the column we are on
 * @param 	int		  $user_id		      req		The ID of the user
 * @return 	mixed 	Our new data for the user column
 *
 * @uses    theme_t_wp_count_user_comments  Counts the comments made by the user with the supplied user_id
 *
 * @since   1.0
 * @author  WP Theme Tutorial, Curtis McHale
 */
function theme_t_wp_comment_count_content( $custom_column, $column_name, $user_id ){

  switch( $column_name ){
    case 'comment_count_by_user':
      return theme_t_wp_count_user_comments( $user_id );
      break;
  }

}
add_filter( 'manage_users_custom_column', 'theme_t_wp_comment_count_content', 10, 3 );
