<?php

use MediaWiki\MediaWikiServices;

return [
	'MWStakeLockdownFactory' => function ( MediaWikiServices $services ) {
		$registry = $services->getService( 'MWStakeManifestRegistryFactory' )
			->get( 'MWStakeLockdownRegistry' );
		return new LockdownFactory( $registry, $GLOBALS['mwsgLockdownRegistry'] );
	}
];
