<?php


class Tribe__Tickets__REST__V1__Post_Repository implements Tribe__Tickets__REST__Interfaces__Post_Repository {

	/**
	 * Returns an array representation of a ticket.
	 *
	 * @since TBD
	 *
	 * @param int    $ticket_id A ticket post ID.
	 * @param string $context  Context of data.
	 *
	 * @return $ticket_id.
	 *
	 */
	public function get_ticket_data( $ticket_id, $context = '' ) {
		return $ticket_id;
	}

}