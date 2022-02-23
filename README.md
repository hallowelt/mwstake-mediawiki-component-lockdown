## MediaWiki Stakeholders Group - Components
# Lockdown for MediaWiki

Provides a service and classes for permission lockdown on single pages

**This code is meant to be executed within the MediaWiki application context. No standalone usage is intended.**

## Use in a MediaWiki extension

Add `"mwstake/mediawiki-component-lockdown": "~1.0"` to the `require` section of your `composer.json` file.

Explicit initialization is required. This can be archived by
- either adding `"callback": "mwsInitComponents"` to your `extension.json`/`skin.json`
- or calling `mwsInitComponents();` within you extensions/skins custom `callback` method

See also [`mwstake/mediawiki-componentloader`](https://github.com/hallowelt/mwstake-mediawiki-componentloader).

### Implement a module

Create a class that implements `MWStake\MediaWiki\Component\Lockdown\IModule`. For convenience you may want to derive directly from the abstract base class `MWStake\MediaWiki\Component\Lockdown\Module`.

### Register a module

[ObjectFactory specification](https://www.mediawiki.org/wiki/ObjectFactory) should be provided.

*Example 1:*
```php
$GLOBALS['mwsgLockdownRegistry']['mymodulename'] = [
    'class' => "\\MediaWiki\Extension\\MyExt\\Lockdown\\LockThisDown",
    'services' => [ 'MainConfig', 'MyOtherService' ]
];
```
*Example 2: (legacy)*
This provides the following services
```php
$GLOBALS['mwsgLockdownRegistry']['mymodulename'][] = "\\MediaWiki\Extension\\MyExt\\Lockdown\\LockThisDown::myCallback"
```
