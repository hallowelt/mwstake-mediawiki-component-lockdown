<?php

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\Lockdown\Factory;
use MWStake\MediaWiki\Component\Lockdown\ModuleFactory;

return [
	'MWStakeLockdownModuleFactory' => function ( MediaWikiServices $services ) {
		$registry = $services->getService( 'MWStakeManifestRegistryFactory' )
			->get( 'MWStakeLockdownRegistry' );
		return new ModuleFactory(
			$registry,
			!empty( $GLOBALS['mwsgLockdownRegistry'] ) ? $GLOBALS['mwsgLockdownRegistry'] : [],
			$services->getMainConfig(),
			// legacy
			$services
		);
	},
	'MWStakeLockdown' => function ( MediaWikiServices $services ) {
		return new Factory(
			$services->getService( 'MWStakeLockdownModuleFactory' ),
			$services->getMainConfig()
		);
	}
];
