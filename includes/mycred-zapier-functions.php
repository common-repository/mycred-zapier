<?php
if ( ! defined( 'myCRED_ZAPIER' ) ) exit;

if ( ! function_exists( 'create_mycred_zapier_table' ) ) :
    function create_mycred_zapier_table() {

    	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		global $wpdb;
		
		$tbl = $wpdb->prefix . 'mycred_zapier';
		
		$tbl_sql = "CREATE TABLE $tbl (
			id            INT(11) NOT NULL AUTO_INCREMENT, 
			user_id       INT(11) NOT NULL, 
			ref           VARCHAR(256) NOT NULL, 
			ref_id        INT(11) DEFAULT NULL, 
			created_time  BIGINT(20) DEFAULT NULL,
			data          LONGTEXT DEFAULT NULL, 
			PRIMARY KEY   (id), 
			UNIQUE KEY id (id),
			INDEX 		  user_id (user_id),
			INDEX 		  ref (ref),
			INDEX 		  ref_id (ref_id),
			INDEX 		  created_time (created_time)
		) AUTO_INCREMENT=10000";   
		
		if ( maybe_create_table( $tbl, $tbl_sql ) ) {

			mycred_update_option( 'mycred_zapier_db_version', MYCRED_ZAPIER_DB_VERSION );

		}
        
    }
endif;

if ( ! function_exists( 'mycred_zapier_insert_log' ) ) :
    function mycred_zapier_insert_log( $user_id, $ref, $ref_id = 0, $data = '' ) {

    	$db_version = mycred_get_option( 'mycred_zapier_db_version', false );

		// DB Not installed
		if ( $db_version != MYCRED_ZAPIER_DB_VERSION ) {

			create_mycred_zapier_table();

		}

    	global $wpdb;
		
		$table_name = $wpdb->prefix . 'mycred_zapier';

		$data = ( is_array( $data ) || is_object( $data ) ) ? serialize( $data ) : $data;

    	$wpdb->insert( 
			$table_name, 
			array( 
				'user_id'      => $user_id,
				'ref'          => $ref,
				'ref_id'       => $ref_id,
				'created_time' => time(),
				'data'         => $data
			) 
		);

		return $wpdb->insert_id;
        
    }
endif;