<?php

namespace MWStake\MediaWiki\Component\Lockdown;

use ConfigFactory;
use Exception;
use IContextSource;
use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\ManifestRegistry\IRegistry;
use Wikimedia\ObjectFactory;

class ModuleFactory implements IModuleFactory {

	/**
	 * @var IRegistry
	 */
	protected $registry = null;

	/**
	 * @var ConfigFactory
	 */
	protected $configFactory = null;

	/**
	 * @var MediaWikiServices
	 */
	protected $services = null;

	/**
	 * @var array
	 */
	protected $globalRegistry = null;

	/**
	 *
	 * @var IModule[]
	 */
	protected $modules = [];

	/**
	 *
	 * @param IRegistry $registry
	 * @param ConfigFactory $configFactory
	 * @param IContextSource $context
	 */
	public function __construct( IRegistry $registry, array $globalRegistry,
		ConfigFactory $configFactory, MediaWikiServices $services ) {
		$this->registry = $registry;
		$this->configFactory = $configFactory;
		$this->globalRegistry = $globalRegistry;
		$this->services = $services;
	}

	/**
	 * @param IContextSource $context
	 * @return Module[]
	 */
	public function getModules( IContextSource $context ): array {
		foreach ( $this->getModuleDefinitions() as $key => $definition ) {
			$module = null;
			if ( is_string( $definition ) && is_callable( $definition ) ) {
				// legacy
				$module = call_user_func_array( $definition, [
					$this->configFactory->makeConfig( 'wg' ),
					$context,
					$this->services,
				] );
			} else {
				try {
					$module = ObjectFactory::getObjectFromSpec( $definition );
				} catch ( Exception $e ) {
					$module = new NULLModule;
				}
			}
			if ( !$module instanceof IModule ) {
				$module = new NULLModule;
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
