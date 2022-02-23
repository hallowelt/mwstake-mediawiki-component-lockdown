<?php

namespace MWStake\MediaWiki\Component\Lockdown;

use Config;
use Exception;
use IContextSource;
use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\Lockdown\Module\NULLModule;
use MWStake\MediaWiki\Component\ManifestRegistry\IRegistry;
use Wikimedia\ObjectFactory;

class ModuleFactory implements IModuleFactory {

	/**
	 * @var IRegistry
	 */
	protected $registry;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var MediaWikiServices
	 */
	protected $services;

	/**
	 * @var array
	 */
	protected $globalRegistry;

	/**
	 *
	 * @var IModule[]
	 */
	protected $modules = [];

	/**
	 *
	 * @param IRegistry $registry
	 * @param array $globalRegistry
	 * @param Config $config
	 * @param MediaWikiServices $services
	 */
	public function __construct( IRegistry $registry, array $globalRegistry,
		Config $config, MediaWikiServices $services ) {
		$this->registry = $registry;
		$this->config = $config;
		$this->globalRegistry = $globalRegistry;
		$this->services = $services;
	}

	/**
	 * @param IContextSource $context
	 * @return Module[]
	 */
	public function getModules( IContextSource $context ): array {
		# error_log(var_export($this->getModuleDefinitions(),1));
		foreach ( $this->getModuleDefinitions() as $key => $definition ) {
			$module = null;
			if ( is_string( $definition ) && is_callable( $definition ) ) {
				// legacy
				$module = call_user_func_array( $definition, [
					$this->config,
					$context,
					$this->services,
				] );
			} else {
				try {
					$module = ObjectFactory::getObjectFromSpec( $definition );
				} catch ( Exception $e ) {
					$module = NULLModule::getInstance( $this->config, $context, $this->services );
				}
			}
			if ( !$module instanceof IModule ) {
				$module = NULLModule::getInstance( $this->config, $context, $this->services );
			}
			$modules[] = $module;
		}
		return $modules;
	}

	/**
	 * @return array
	 */
	protected function getModuleDefinitions(): array {
		$definitions = [];
		foreach ( $this->registry->getAllKeys() as $key ) {
			$definitions[$key] = $this->registry->getValue( $key );
		}
		return array_merge( $definitions, $this->globalRegistry );
	}

}
