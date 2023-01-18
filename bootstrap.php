<?php

if ( !defined( 'MEDIAWIKI' ) && !defined( 'MW_PHPUNIT_TEST' ) ) {
	return;
}

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_LOCKDOWN_VERSION' ) ) {
	return;
}

if ( !defined( 'MWSTAKE_MEDIAWIKI_COMPONENTLOADER_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_LOCKDOWN_VERSION', '1.0.1' );

MWStake\MediaWiki\ComponentLoader\Bootstrapper::getInstance()
->register( 'lockdown', function () {
	if ( !isset( $GLOBALS['mwsgLockdownRegistry'] ) ) {
		$GLOBALS['mwsgLockdownRegistry'] = [];
	}
	if ( !isset( $GLOBALS['mwsgLockdownGroupModuleRegistry'] ) ) {
		$GLOBALS['mwsgLockdownGroupModuleRegistry'] = [];
	}
	$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/includes/ServiceWiring.php';
	$GLOBALS['wgHooks']['getUserPermissionsErrors'][] = "\\MWStake\\MediaWiki\\Component\\Lockdown"
		. "\\Hook\\ApplyLockdown::onGetUserPermissionsErrors";
} );
