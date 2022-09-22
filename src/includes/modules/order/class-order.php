<?php

namespace LokusWP\Commerce;

class Order {
	public function __construct() {
	}

	public static function set_status( $trx_id, $status = "completed", $notification = true ) {
		$status = sanitize_key( $status );

		$status_list               = [];
		$status_list['pending']    = 'pending';
		$status_list['paid']       = 'paid';
		$status_list['processing'] = 'processing';
		$status_list['pickup']     = 'pickup';
		$status_list['cancelled']  = 'cancelled';
		$status_list['shipped']    = 'shipped';
		$status_list['completed']  = 'completed';

		if ( isset( $status_list[ $status ] ) ) {
			$status = $status_list[ $status ];

			// Update Order Status
			lwc_update_order_meta( $trx_id, "_order_status", $status );

			// Set Notification
			if ( $notification ) {
				\as_schedule_single_action( strtotime( '+1 seconds' ), 'lokuswp_notification', array( $trx_id . '-' . $status ), "lwcommerce" );
			}

			// Another Action for Order Status
			switch ( $status ) {
				case 'pending':
					break;
				case 'processing':
				case 'paid':
					self::set_paid( $trx_id );
					break;
			}
		}
	}

	public static function set_paid( $trx_id ) {

		// Update Paid_at Column
		lwp_transaction_update_column( $trx_id, "paid_at", lwp_current_date() );
		lwp_transaction_update_column( $trx_id, "status", 'paid' );

	}

}

