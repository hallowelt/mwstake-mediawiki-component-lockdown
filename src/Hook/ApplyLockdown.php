<?php

namespace MWStake\MediaWiki\Component\Lockdown\Hook;

use MediaWiki\MediaWikiServices;

class ApplyLockdown {
	/**
	 * @param Title $title
	 * @param User $user
	 * @param string $action
	 * @param string &$result
	 * @return bool
	 */
	public static function onGetUserPermissionsErrors( $title, $user, $action, &$result ) {
		$lockdown = MediaWikiServices::getInstance()->getService( 'MWStakeLockdown' )
			->newFromTitleAndUserRelation( $title, $user );
		if ( !$lockdown->isLockedDown( $action ) ) {
			return true;
		}
		$result = $lockdown->getLockState( $action )->getMessage();
		return false;
	}

}
