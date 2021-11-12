<?php

namespace MWStake\MediaWiki\Component\Lockdown;

use IContextSource;
use MediaWiki\Permissions\PermissionManager;
use Status;
use Title;
use User;

interface IFactory {

	/**
	 * @param Title $title
	 * @param User $user
	 * @param IContextSource|null $context
	 * @return Lockdown
	 */
	public function newFromTitleAndUserRelation( Title $title, User $user,
		IContextSource $context = null ): Lockdown;

	/**
	 * @param Title $title
	 * @param string $action
	 * @param User|null $user
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary as needed
	 * @param IContextSource|null $context
	 * @return Status
	 */
	public function userCan( Title $title, $action = 'read', User $user = null,
		$rigor = PermissionManager::RIGOR_SECURE, IContextSource $context = null ): Status;
}
