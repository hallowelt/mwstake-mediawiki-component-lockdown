<?php

namespace MWStake\MediaWiki\Component\Lockdown;

use Config;
use IContextSource;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use RequestContext;
use Status;
use Title;
use User;

class Factory implements IFactory {

	/**
	 * @var IModuleFactory
	 */
	protected $moduleFactory;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Lockdown[]
	 */
	protected $instances = [];

	/**
	 *
	 * @param IModuleFactory $moduleFactory
	 * @param Config $config
	 */
	public function __construct( IModuleFactory $moduleFactory, Config $config ) {
		$this->moduleFactory = $moduleFactory;
		$this->config = $config;
	}

	/**
	 * @param Title $title
	 * @param User $user
	 * @param IContextSource|null $context
	 * @return Lockdown
	 */
	public function newFromTitleAndUserRelation( Title $title, User $user,
		?IContextSource $context = null ): Lockdown {
		if ( !$context ) {
			$context = RequestContext::getMain();
		}
		$instance = $this->getLockownFromCache( $title, $user );
		if ( $instance ) {
			return $instance;
		}
		return $this->newLockdown( $title, $user, $context );
	}

	/**
	 *
	 * @param Title $title
	 * @param User $user
	 * @param IContextSource $context
	 * @return Lockdown
	 */
	protected function newLockdown( Title $title, User $user, IContextSource $context ): Lockdown {
		$lockdown = new Lockdown(
			$this->config,
			$title,
			$user,
			$this->moduleFactory->getModules( $context )
		);
		$this->instances[$this->getCacheKey( $title, $user )] = $lockdown;
		return $lockdown;
	}

	/**
	 * @param Title $title
	 * @param User $user
	 * @return Lockdown|null
	 */
	protected function getLockownFromCache( Title $title, User $user ): ?Lockdown {
		if ( isset( $this->instances[$this->getCacheKey( $title, $user )] ) ) {
			return $this->instances[$this->getCacheKey( $title, $user )];
		}
		return null;
	}

	/**
	 * @param Title $title
	 * @param User $user
	 * @return string
	 */
	protected function getCacheKey( Title $title, User $user ): string {
		return "{$title->getArticleID()}-{$user->getId()}";
	}

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
	public function userCan( Title $title, $action = 'read', ?User $user = null,
		$rigor = PermissionManager::RIGOR_SECURE, ?IContextSource $context = null ): Status {
		$status = Status::newGood();
		if ( !$context ) {
			$context = RequestContext::getMain();
		}
		if ( !$user ) {
			$user = $context->getUser();
		}
		$result = MediaWikiServices::getInstance()->getPermissionManager()->userCan(
			$action,
			$user,
			$title,
			$rigor
		);
		if ( !$result ) {
			$lockdown = $this->newFromTitleAndUserRelation( $title, $user, $context );
			$status->merge( $lockdown->getLockState( $action ) );
		}

		return $status;
	}

}
