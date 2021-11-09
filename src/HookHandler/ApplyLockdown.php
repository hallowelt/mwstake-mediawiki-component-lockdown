<?php

namespace BlueSpice\Hook\GetUserPermissionsErrors;

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Hook\GetUserPermissionsErrorsHook;

class ApplyLockdown implements GetUserPermissionsErrorsHook {
	/**
	 * @param Title $title
	 * @param User $user
	 * @param string $action
	 * @param string &$result
	 * @return bool
	 */
	public function onGetUserPermissionsErrors( $title, $user, $action, &$result ) {
		$lockdown = MediaWikiServices::getInstance()->getService( 'BSPermissionLockdownFactory' )
			->newFromTitleAndUserRelation( $title, $user );
		if ( !$this->getLockdown()->isLockedDown( $action ) ) {
			return true;
		}
		$result = $lockdown->getLockState( $this->action )->getMessage();
		return false;
	}

}
