<?php
/*
Plugin Name: Movie Gallery
Plugin URI: http://www.rodrigorusso.com.br
description: Card Flipper
Version: 1.0
Author: RussoFaccin
Author URI: http://www.rodrigorusso.com.br
License: GPL2
*/

/*
| ###########################################################################
|   POST TYPE
| ###########################################################################
*/

add_action( 'init', 'create_moviegallery_post_type' );

function create_moviegallery_post_type() {
  $labels = [
    'name' => 'Movie Gallery',
    'singular_name' => 'Movie',
    'add_new' => 'New Movie',
    'add_new_item' => 'Add New Movie',
    'edit_item' => 'Edit Movie'
  ];
  $args = [
    'labels' => $labels,
    'description' => 'Movie Gallery',
    'public' => true,
    'publicly_queryable' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-tickets-alt',
    'supports' => 'title',
    'show_in_rest' => true
  ];
  register_post_type('movie_gallery', $args);
}

/*
| ###########################################################################
|   META BOXES
| ###########################################################################
*/

add_action( 'add_meta_boxes', 'add_meta_boxes');
function add_meta_boxes() {
  // Title
  add_meta_box(
    'movie_thumb',
    'Thumbnail',
    'movie_thumb_cb',
    'movie_gallery'
  );
}
function movie_thumb_cb($post) {
  $value = get_post_custom_values('movie_thumb', $post->ID);
  $thumb = empty($value[0]) ? plugins_url('', __FILE__).'/img/add-new-image.png"' : $value[0];
  $html .= '<img class="a-selectThumb" src="'.$thumb.'" alt="">';
  $html .= '<input class="a-thumbPath" type="hidden" name="fld_thumb">';
  echo $html;
}

/*
| ###########################################################################
|   SAVE POST
| ###########################################################################
*/

add_action( 'save_post', 'on_save_post');

function on_save_post($postid) {
  $thumbPath = $_POST['fld_thumb'];
  update_post_meta($postid, 'movie_thumb', $thumbPath);
}

/*
| ###########################################################################
|   META FIELD IN REST
| ###########################################################################
*/

add_action( 'rest_api_init', 'slug_register_movieThumb' );

function slug_register_movieThumb() {
    register_rest_field( 'movie_gallery',
        'thumbnail',
        array(
            'get_callback'    => 'slug_get_movie',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function slug_get_movie( $object, $field_name, $request ) {
  return get_post_custom_values('movie_thumb', $object['id'])[0];
  // return get_post_meta( $object['id'], $field_name, true );
}

/*
| ###########################################################################
|   SCRIPTS && STYLES
| ###########################################################################
*/

add_action('admin_enqueue_scripts', 'enqueue_media', 0 );

function enqueue_media() {
  wp_enqueue_media();
  // CSS
  wp_enqueue_style( 'movie_gallery_css', plugins_url('css/movie-gallery.css', __FILE__) );
  // JS
  $IN_FOOTER = true;
  wp_enqueue_script( 'movie_gallery_js', plugins_url('js/movie-gallery.js', __FILE__), null, null, $IN_FOOTER);
}