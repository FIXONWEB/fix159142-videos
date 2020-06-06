<?php

/**
 * Registers the `playlist` post type.
 */
function playlist_init() {
	register_post_type( 'playlist', array(
		'labels'                => array(
			'name'                  => __( 'Youtube Playlists', 'fix159142-videos' ),
			'singular_name'         => __( 'Youtube Playlist', 'fix159142-videos' ),
			'all_items'             => __( 'All Youtube Playlists', 'fix159142-videos' ),
			'archives'              => __( 'Youtube Playlist Archives', 'fix159142-videos' ),
			'attributes'            => __( 'Youtube Playlist Attributes', 'fix159142-videos' ),
			'insert_into_item'      => __( 'Insert into Youtube Playlist', 'fix159142-videos' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Youtube Playlist', 'fix159142-videos' ),
			'featured_image'        => _x( 'Featured Image', 'playlist', 'fix159142-videos' ),
			'set_featured_image'    => _x( 'Set featured image', 'playlist', 'fix159142-videos' ),
			'remove_featured_image' => _x( 'Remove featured image', 'playlist', 'fix159142-videos' ),
			'use_featured_image'    => _x( 'Use as featured image', 'playlist', 'fix159142-videos' ),
			'filter_items_list'     => __( 'Filter Youtube Playlists list', 'fix159142-videos' ),
			'items_list_navigation' => __( 'Youtube Playlists list navigation', 'fix159142-videos' ),
			'items_list'            => __( 'Youtube Playlists list', 'fix159142-videos' ),
			'new_item'              => __( 'New Youtube Playlist', 'fix159142-videos' ),
			'add_new'               => __( 'Add New', 'fix159142-videos' ),
			'add_new_item'          => __( 'Add New Youtube Playlist', 'fix159142-videos' ),
			'edit_item'             => __( 'Edit Youtube Playlist', 'fix159142-videos' ),
			'view_item'             => __( 'View Youtube Playlist', 'fix159142-videos' ),
			'view_items'            => __( 'View Youtube Playlists', 'fix159142-videos' ),
			'search_items'          => __( 'Search Youtube Playlists', 'fix159142-videos' ),
			'not_found'             => __( 'No Youtube Playlists found', 'fix159142-videos' ),
			'not_found_in_trash'    => __( 'No Youtube Playlists found in trash', 'fix159142-videos' ),
			'parent_item_colon'     => __( 'Parent Youtube Playlist:', 'fix159142-videos' ),
			'menu_name'             => __( 'Youtube Playlists', 'fix159142-videos' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title', 'editor' ),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'playlist',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'playlist_init' );

/**
 * Sets the post updated messages for the `playlist` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `playlist` post type.
 */
function playlist_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['playlist'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Youtube Playlist updated. <a target="_blank" href="%s">View Youtube Playlist</a>', 'fix159142-videos' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'fix159142-videos' ),
		3  => __( 'Custom field deleted.', 'fix159142-videos' ),
		4  => __( 'Youtube Playlist updated.', 'fix159142-videos' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Youtube Playlist restored to revision from %s', 'fix159142-videos' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Youtube Playlist published. <a href="%s">View Youtube Playlist</a>', 'fix159142-videos' ), esc_url( $permalink ) ),
		7  => __( 'Youtube Playlist saved.', 'fix159142-videos' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Youtube Playlist submitted. <a target="_blank" href="%s">Preview Youtube Playlist</a>', 'fix159142-videos' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Youtube Playlist scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Youtube Playlist</a>', 'fix159142-videos' ),
		date_i18n( __( 'M j, Y @ G:i', 'fix159142-videos' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Youtube Playlist draft updated. <a target="_blank" href="%s">Preview Youtube Playlist</a>', 'fix159142-videos' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'playlist_updated_messages' );
