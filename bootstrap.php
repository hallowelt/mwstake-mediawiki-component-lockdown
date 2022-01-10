<?php

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_LOCKDOWN_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_LOCKDOWN_VERSION', '1.0.0' );

MWStake\MediaWiki\ComponentLoader\Bootstrapper::getInstance()
->register( 'lockdown', function () {
	if ( !isset( $GLOBALS['mwsgLockdownRegistry'] ) ) {
		$GLOBALS['mwsgLockdownRegistry'] = [];
	}
	$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/includes/ServiceWiring.php';

	$GLOBALS['wgHooks']['GetUserPermissionsErrors'][] = "\\MWStake\\MediaWiki\\Component\\Lockdown"
		. "\\Hook\\ApplyLockdown::onGetUserPermissionsErrors";
} );
