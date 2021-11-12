<?php

namespace MWStake\MediaWiki\Component\Lockdown;

interface IModuleFactory {

	/**
	 * @param IModule[] $modules
	 * @return IModule[]
	 */
	public function getModules(): array;

}
