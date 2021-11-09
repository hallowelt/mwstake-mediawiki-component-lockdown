<?php

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_MANIFESTREGISTRY_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_MANIFESTREGISTRY_VERSION', '1.0.0' );

if ( !isset( $GLOBALS['mwsgLockdownRegistry'] ) ) {
	$GLOBALS['mwsgLockdownRegistry'] = [];
}
$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/includes/ServiceWiring.php';

$GLOBALS['wgHooks']['GetUserPermissionsErrors'][] = "\\MWStake\\MediaWiki\\Component\\Lockdown"
	. "\\Hook\\ApplyLockdown::onGetUserPermissionsErrors";
