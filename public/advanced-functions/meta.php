<?php

// Don't load directly
if ( !defined( 'ABSPATH' ) ) die( '-1' );

if ( class_exists( 'Tribe_Meta_Factory' ) ) {

	class Tribe_Register_Meta {
		function the_title() {
			return get_the_title( get_the_ID() );
		}
		function event_date( $meta_id ) {
			if ( tribe_get_start_date() !== tribe_get_end_date() ) {
				// Start & end date
				$html = Tribe_Meta_Factory::template(
					__( 'Start:', 'tribe-events-calendar' ),
					sprintf( '<abbr class="tribe-events-abbr" title="%s">%s</abbr>',
						tribe_get_end_date( null, false, TribeDateUtils::DBDATEFORMAT ),
						tribe_get_end_date()
					),
					$meta_id );
				$html .= Tribe_Meta_Factory::template(
					__( 'End:', 'tribe-events-calendar' ),
					sprintf( '<abbr class="tribe-events-abbr" title="%s">%s</abbr>',
						tribe_get_end_date( null, false, TribeDateUtils::DBDATEFORMAT ),
						tribe_get_end_date()
					),
					$meta_id );
			} else {
				// If all day event, show only start date
				$html = Tribe_Meta_Factory::template(
					__( 'Date:', 'tribe-events-calendar' ),
					sprintf( '<abbr class="tribe-events-abbr" title="%s">%s</abbr>',
						tribe_get_start_date( null, false, TribeDateUtils::DBDATEFORMAT ),
						tribe_get_start_date()
					),
					$meta_id );
			}
			return apply_filters( 'tribe_event_meta_event_date', $html );
		}

		function event_category( $meta_id ) {
			global $tribe_meta_factory;
			$post_id = get_the_ID();

			// setup classes in the template
			$template = Tribe_Meta_Factory::embed_classes( $tribe_meta_factory->meta[$meta_id]['wrap'], $tribe_meta_factory->meta[$meta_id]['classes'] );

			$args = array(
				'before' => '',
				'sep' => ', ',
				'after' => '',
				'label' => null,
				'label_before' => $template['label_before'],
				'label_after' => $template['label_after'],
				'wrap_before' => $template['meta_before'],
				'wrap_after' => $template['meta_after']
			);
			// Event categories
			return apply_filters( 'tribe_event_meta_event_category', tribe_get_event_categories( $post_id, $args ) );
		}

		function event_tag( $meta_id ) {
			global $tribe_meta_factory;
			return apply_filters( 'tribe_event_meta_event_tag', tribe_meta_event_tags( $tribe_meta_factory->meta[$meta_id]['label'], ', ', false ) );
		}

		function event_website( $meta_id ){
			global $tribe_meta_factory;
			$link = tribe_get_event_website_link();
			$website_link = empty( $link ) ? '' :  Tribe_Meta_Factory::template(
				$tribe_meta_factory->meta[$meta_id]['label'],
				$link,
				$meta_id );
			return apply_filters( 'tribe_event_meta_event_website', $website_link );
		}
		
		function event_origin( $meta_id ) {
			global $tribe_meta_factory;
			$origin_to_display = apply_filters( 'tribe_events_display_event_origin', '', get_the_ID() );
			$origin = empty( $link ) ? '' :  Tribe_Meta_Factory::template(
				$tribe_meta_factory->meta[$meta_id]['label'],
				$origin_to_display,
				$meta_id );
			return apply_filters( 'tribe_event_meta_event_orgin', $origin );
		}

		function organizer_name( $meta_id ){
			global $tribe_meta_factory;
			$post_id = get_the_ID();
			$name = tribe_get_organizer( $post_id );
			$organizer_name = empty( $name ) ? '' :  Tribe_Meta_Factory::template(
				$tribe_meta_factory->meta[$meta_id]['label'],
				$name,
				$meta_id );
			return apply_filters( 'tribe_event_meta_organizer_name', $organizer_name, $meta_id );
		}

		function organizer_email( $meta_id ){
			global $tribe_meta_factory;
			$email = tribe_get_organizer_email();
			$organizer_email = empty( $email ) ? '' :  Tribe_Meta_Factory::template(
				$tribe_meta_factory->meta[$meta_id]['label'],
				'<a href="mailto:' . $email . '">' . $email . '</a>',
				$meta_id );
			return apply_filters( 'tribe_event_meta_organizer_email', $organizer_email );
		}

		function venue_name( $meta_id ){
			global $tribe_meta_factory;
			$post_id = get_the_ID();
			$name = class_exists( 'TribeEventsPro' ) ? // If pro, show venue w/ link
					tribe_get_venue_link( $post_id, false ) :
					tribe_get_venue( $post_id ); // Otherwise show venue name

			// wrap the name with a link if PRO is active
			if( ! empty( $name ) && class_exists( 'TribeEventsPro' ) ){
				$name = '<a href="'.$name.'">'.tribe_get_venue($post_id).'</a>';
			}

			$venue_name = empty( $name ) ? '' :  Tribe_Meta_Factory::template(
				$tribe_meta_factory->meta[$meta_id]['label'],
				$name,
				$meta_id );
			return apply_filters( 'tribe_event_meta_venue_name', $venue_name );
		}

		function venue_address( $meta_id ){
			global $tribe_meta_factory;

			$address = tribe_address_exists( get_the_ID() ) ? '<address class="event-address">' . tribe_get_full_address( get_the_ID() ) . '</address>' : '';

			// Google map link
			$gmap_link = tribe_show_google_map_link( get_the_ID() ) ? self::gmap_link() : '' ;

			$venue_address = empty( $address ) ? '' :  Tribe_Meta_Factory::template(
				$tribe_meta_factory->meta[$meta_id]['label'],
				$address . $gmap_link,
				$meta_id );
			return apply_filters( 'tribe_event_meta_venue_address', $venue_address );
		}

		function venue_map( $meta_id ){
			global $tribe_meta_factory;
			$post_id = get_the_ID();
			$map = tribe_get_embedded_map( $post_id );
			$venue_map = empty( $map ) ? '' :  Tribe_Meta_Factory::template(
				$tribe_meta_factory->meta[$meta_id]['label'],
				$map,
				$meta_id );
			return apply_filters( 'tribe_event_meta_venue_map', $venue_map );
		}

		function gmap_link() {
			$link = sprintf('<a class="tribe-events-gmap" href="%s" title="%s" target="_blank">%s</a>',
				tribe_get_map_link(),
				__( 'Click to view a Google Map', 'tribe-events-calendar' ),
				__( 'Google Map', 'tribe-events-calendar' )
				);
			return apply_filters( 'tribe_event_meta_gmap_link', $link);
		}

	}

	/**
	 * Register Meta Group: Event Details
	 */
	tribe_register_meta_group( 'tribe_event_details', array(
			'label' => __('Details', 'tribe-events-calendar' ),
			'classes' => array(
				'before'=>array('tribe-events-meta-group'),
				'label_before'=>array('tribe-event-single-section-title'))
			// 'wrap' => $tribe_events_default_meta_group_templates
		) );

	/**
	 * Register Meta: Event Date (Start/End or Date)
	 *
	 * @group tribe_event_details
	 */
	tribe_register_meta( 'tribe_event_date', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array( 'meta_before' => array('tribe-events-date')),
			'group' => 'tribe_event_details',
			'priority' => 10,
			'filter_callback' => array( 'Tribe_Register_Meta', 'event_date' )
		) );

	/**
	 * Register Meta: Event Cost
	 *
	 * @group tribe_event_details
	 */
	tribe_register_meta( 'tribe_event_cost', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array( 'meta_before' => array('tribe-events-event-cost') ),
			'label' => __( 'Cost:', 'tribe-events-calendar' ),
			'priority' => 20,
			'callback' => 'tribe_get_cost',
			'group' => 'tribe_event_details',
			'show_on_meta' => true
		) );

	/**
	 * Register Meta: Event Categories
	 *
	 * @group tribe_event_details
	 */
	tribe_register_meta( 'tribe_event_category', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array( 'meta_before' => array( 'tribe-events-event-categories')),
			'filter_callback' => array( 'Tribe_Register_Meta', 'event_category' ),
			'priority' => 30,
			'group' => 'tribe_event_details'
		) );

	/**
	 * Register Meta: Event Tags
	 *
	 * @group tribe_event_details
	 */
	tribe_register_meta( 'tribe_event_tag', array(
			'label' => __( 'Event Tags:', 'tribe-events-calendar' ),
			'filter_callback' => array( 'Tribe_Register_Meta', 'event_tag' ),
			'priority' => 40,
			'group' => 'tribe_event_details'
		) );

	/**
	 * Register Meta: Event Website
	 *
	 * @group tribe_event_details
	 */
	tribe_register_meta( 'tribe_event_website', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array( 'meta_before' => array('published', 'event-url')),
			'label' => __( 'Website:', 'tribe-events-calendar' ),
			'filter_callback' => array('Tribe_Register_Meta', 'event_website'),
			'priority' => 50,
			'group' => 'tribe_event_details'
		) );

	/**
	 * Register Meta: Event Origin
	 *
	 * @group tribe_event_details
	 */
	tribe_register_meta( 'tribe_event_origin', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array('meta_before'=>array('published','event-origin')),
			'label' => __( 'Origin:', 'tribe-events-calendar' ),
			'filter_callback' => array( 'Tribe_Register_Meta', 'event_origin' ),
			'priority' => 60,
			'group' => 'tribe_event_details'
		) );

	/**
	 * Register Meta Group: Event Venue
	 */
	tribe_register_meta_group( 'tribe_event_venue', array(
			'label' => __('Venue', 'tribe-events-calendar' ),
			'classes' => array(
				'before'=>array('tribe-events-meta-group'),
				'label_before'=>array('tribe-event-single-section-title'))
			// 'wrap' => $tribe_events_default_meta_group_templates
		) );

	/**
	 * Register Meta: Venue Name
	 *
	 * @group tribe_event_venue
	 */
	tribe_register_meta( 'tribe_event_venue_name', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array('meta_before'=> array('vcard','fn','org')),
			'label' => '',
			'priority' => 10,
			'filter_callback' => array( 'Tribe_Register_Meta', 'venue_name' ),
			'group' => 'tribe_event_venue'
		) );

	/**
	 * Register Meta: Venue Phone
	 *
	 * @group tribe_event_venue
	 */
	tribe_register_meta( 'tribe_event_venue_phone', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array('meta_before'=>array('vcard','tel')),
			'label' => __( 'Phone:', 'tribe-events-calendar' ),
			'priority' => 20,
			'callback' => 'tribe_get_phone',
			'group' => 'tribe_event_venue'
		) );

	/**
	 * Register Meta: Venue Address
	 *
	 * @group tribe_event_venue
	 */
	tribe_register_meta( 'tribe_event_venue_address', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array('meta_before' =>array('location')),
			'priority' => 30,
			'label' => __( 'Address:', 'tribe-events-calendar' ),
			'filter_callback' => array( 'Tribe_Register_Meta', 'venue_address' ),
			'group' => 'tribe_event_venue'
		) );

	/**
	 * Register Meta: Venue Phone
	 *
	 * @group tribe_event_venue
	 */
	tribe_register_meta( 'tribe_event_venue_website', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array('meta_before'=>array('vcard','url')),
			'label' => __( 'Website:', 'tribe-events-calendar' ),
			'priority' => 40,
			'callback' => 'tribe_get_venue_website_link',
			'group' => 'tribe_event_venue'
		) );

	/**
	 * Register Meta Group: Event Organizer
	 */
	tribe_register_meta_group( 'tribe_event_organizer', array(
			'label' => __('Organizer', 'tribe-events-calendar' ),
			'classes' => array(
				'before'=>array('tribe-events-meta-group'),
				'label_before'=>array('tribe-event-single-section-title'))
			// 'wrap' => $tribe_events_default_meta_group_templates
		) );

	/**
	 * Register Meta: Organizer Name (author)
	 *
	 * @group tribe_event_organizer
	 */
	tribe_register_meta( 'tribe_event_organizer_name', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes'=>array('meta_before'=>array('vcard','author','fn','org')),
			'label' => '',
			'priority' => 10,
			'filter_callback' => array( 'Tribe_Register_Meta', 'organizer_name' ),
			'group' => 'tribe_event_organizer'
		) );

	/**
	 * Register Meta: Organizer Phone
	 *
	 * @group tribe_event_organizer
	 */
	tribe_register_meta( 'tribe_event_organizer_phone', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array('meta_before'=> array( 'vcard', 'tel') ),
			'label' => __( 'Phone:', 'tribe-events-calendar' ),
			'priority' => 20,
			'callback' => 'tribe_get_organizer_phone',
			'group' => 'tribe_event_organizer'
		) );

	/**
	 * Register Meta: Organizer Email
	 *
	 * @group tribe_event_organizer
	 */
	tribe_register_meta( 'tribe_event_organizer_email', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes'=> array('meta_before'=>array('vcard','email')),
			'label' => __( 'Email:', 'tribe-events-calendar' ),
			'priority' => 30,
			'filter_callback' => array( 'Tribe_Register_Meta', 'organizer_email' ),
			'group' => 'tribe_event_organizer'
		) );

	/**
	 * Register Meta: Organizer Website
	 *
	 * @group tribe_event_organizer
	 */
	tribe_register_meta( 'tribe_event_organizer_website', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes' => array('meta_before'=>array('vcard','url')),
			'label' => __( 'Website:', 'tribe-events-calendar' ),
			'priority' => 40,
			'callback' => 'tribe_get_organizer_website_link',
			'group' => 'tribe_event_organizer'
		) );

	/**
	 * Register Meta: Event Title
	 *
	 * @group none specified
	 */
	tribe_register_meta( 'tribe_event_title', array(
			// 'wrap' => $tribe_events_default_meta_templates,
			'classes'=>array('meta_before'=>array('summary')),
			'label' => __( 'Event:', 'tribe-events-calendar' ),
			'callback' => array( 'Tribe_Register_Meta', 'the_title' )
		) );
	tribe_register_meta( 'tribe_venue_map', array(
			'wrap' => array(
				'before'=>'',
				'after'=>'',
				'label_before'=>'',
				'label_after'=>'',
				'meta_before'=>'<div class="%s">',
				'meta_after'=>'</div>'
			),
			'classes'=>array('meta_before'=>array('tribe-event-venue-map')),
			'label' => '',
			'priority' => 10,
			'filter_callback' => array( 'Tribe_Register_Meta', 'venue_map' )
		) );
	tribe_register_meta( 'tribe_event_venue_gmap_link', array(
			'wrap' => array(
				'before'=>'',
				'after'=>'',
				'label_before'=>'',
				'label_after'=>'',
				'meta_before'=>'',
				'meta_after'=>''
			),
			'label' => '',
			'filter_callback' => array( 'Tribe_Register_Meta', 'gmap_link' ),
		));
}
