<div class="input_block ticket_advanced_Tribe__Tickets__RSVP tribe-dependent" data-depends="#Tribe__Tickets__RSVP_radio" data-condition-is-checked>
	<label for="Tribe__Tickets__RSVP_stock" class="ticket_form_label ticket_form_left"><?php esc_html_e( 'Capacity:', 'event-tickets' ); ?></label>
	<input type='text' id='Tribe__Tickets__RSVP_stock' name='ticket_stock' class="ticket_field ticket_stock ticket_form_right" size='7' value='<?php echo esc_attr( $stock ); ?>'/>
	<span class="tribe_soft_note ticket_form_right"><?php esc_html_e( 'Leave blank for unlimited', 'event-tickets' ); ?></span>
</div>