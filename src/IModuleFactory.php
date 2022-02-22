<?php

namespace MWStake\MediaWiki\Component\Lockdown;

use IContextSource;

interface IModuleFactory {

	/**
	 * @param IContextSource $context
	 * @return IModule[]
	 */
	public function getModules( IContextSource $context ): array;

}
