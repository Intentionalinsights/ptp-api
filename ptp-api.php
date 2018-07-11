<?php
/*
* Plugin Name: Pro-Truth Pledge API
* Description: Allows a trusted part to check if emails addresses have signed the pledge.
* Version: 1.0
* Author: Bentley Davis
* Author URI: https://BentleyDavis.com
*/

/**
 * Grab latest post title by an author!
 *
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,  * or null if none.
 */

$config = require './config.php';

$apiKey = $config['apiKey'];

function api_check_email( $request ) {

  global $apiKey;

  $data = json_decode($request->get_body());

  //Make sure it is a valid key
  if ($data->key != $apiKey) {
	  return;
  }

  global $wpdb;

  $sql = "SELECT * FROM {$wpdb->prefix}ptp_pledges";
  $sql .= ' WHERE ';
  $sql .= ' email = "' . esc_sql( $data->email) . '"';
  $sql .= " LIMIT 1";

$result = $wpdb->get_results( $sql, 'ARRAY_A' );

  return $wpdb->num_rows;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'ptp/v1', '/check-email', array(
    'methods' => 'POST',
    'callback' => 'api_check_email',
  ) );
} );
