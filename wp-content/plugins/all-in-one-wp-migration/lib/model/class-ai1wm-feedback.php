<?php
/**
 * Copyright (C) 2014-2016 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

class Ai1wm_Feedback {

	/**
	 * Submit customer feedback to ServMask.com
	 *
	 * @param  string  $type    Feedback Type
	 * @param  string  $email   User E-mail
	 * @param  string  $message User Message
	 * @param  integer $terms   User Accept Terms
	 *
	 * @return array
	 */
	public function add( $type, $email, $message, $terms ) {
		$errors = array();

		// Submit feedback to ServMask
		if ( empty( $type ) ) {
			$errors[] = __( 'Feedback type is invalid.', AI1WM_PLUGIN_NAME );
		} else if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$errors[] = __( 'Your email is not valid.', AI1WM_PLUGIN_NAME );
		} else if ( empty( $message ) ) {
			$errors[] = __( 'Please enter comments in the text area.', AI1WM_PLUGIN_NAME );
		} else if ( empty( $terms ) ) {
			$errors[] = __( 'Please accept feedback term conditions.', AI1WM_PLUGIN_NAME );
		} else {
			$response = wp_remote_post(
				AI1WM_FEEDBACK_URL,
				array(
					'body' => array(
						'type'              => $type,
						'email'             => $email,
						'message'           => $message,
						'export_options'    => json_encode( get_site_option( AI1WM_EXPORT_OPTIONS, array() ) ),
						'error_handler'     => json_encode( get_site_option( AI1WM_ERROR_HANDLER, array() ) ),
						'exception_handler' => json_encode( get_site_option( AI1WM_EXCEPTION_HANDLER, array() ) ),
					),
				)
			);

			if ( is_wp_error( $response ) ) {
				$errors[] = sprintf( __( 'Something went wrong: %s', AI1WM_PLUGIN_NAME ), $response->get_error_message() );
			}
		}

		return array( 'errors' => $errors );
	}
}
