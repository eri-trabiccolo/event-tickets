<?php
/**
 * Block: RSVP
 * Form from rsvp/form/form.php via AJAX
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/blocks/rsvp/form.php
 *
 * See more documentation about our Blocks Editor templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.9
 *
 */

$going      = $this->get( 'going' );
$must_login = ! is_user_logged_in() && tribe( 'tickets.rsvp' )->login_required();
?>
<!-- This div is where the AJAX returns the form -->
<div class="tribe-block__rsvp__form">
	<?php if ( ! empty( $going ) && ! $must_login ) :
		error_log( $going );
		$ticket = $this->get( 'ticket' );
		$args = array(
					'ticket_id' => $ticket->ID,
					'ticket'    => tribe( 'tickets.rsvp' )->get_ticket( get_the_id(), $ticket->ID ),
					'going'     => esc_html( $going ),
				);

		echo tribe( 'tickets.editor.template' )->template( 'blocks/rsvp/form/form', $args, false );
	endif; ?>
</div>
