<?php

use MediaWiki\MediaWikiServices;

return [
	'MWStakeLockdownFactory' => function ( MediaWikiServices $services ) {
		return new LockdownFactory( $GLOBALS['mwsgLockdownRegistry'] );
	}
];
