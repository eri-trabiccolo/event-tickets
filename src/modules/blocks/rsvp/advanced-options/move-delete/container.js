/**
 * External dependencies
 */
import { connect } from 'react-redux';
import { compose } from 'redux';
import { dispatch as wpDispatch } from '@wordpress/data';

/**
 * Internal dependencies
 */
import Template from './template';

import { withStore } from '@moderntribe/common/hoc';
import {
	actions,
	selectors,
	thunks,
} from '@moderntribe/tickets/data/blocks/rsvp';

const mapStateToProps = ( state ) => ( {
	created: selectors.getRSVPCreated( state ),
	rsvpId: selectors.getRSVPId( state ),
} );

const mapDispatchToProps = ( dispatch, ownProps ) => ( {
	moveRSVP: () => console.warn( 'Implement me' ),
	dispatch,
} );

const mergeProps = ( stateProps, dispatchProps, ownProps ) => {
	const { dispatch, ...restDispatchProps } = dispatchProps;

	return {
		...ownProps,
		...stateProps,
		...restDispatchProps,
		removeRSVP: () => {
			if ( window.confirm( 'Are you sure you want to delete this RSVP? It cannot be undone.' ) ) { // eslint-disable-line no-alert
				dispatch( actions.deleteRSVP() );
				if ( stateProps.created && stateProps.rsvpId ) {
					dispatch( thunks.deleteRSVP( stateProps.rsvpId ) );
				}
				wpDispatch( 'core/editor' ).removeBlocks( [ ownProps.clientId ] );
			}
		},
	};
};

export default compose(
	withStore(),
	connect( mapStateToProps, mapDispatchToProps, mergeProps ),
)( Template );
