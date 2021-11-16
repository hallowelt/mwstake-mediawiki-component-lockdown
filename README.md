## MediaWiki Stakeholders Group - Components
# Lockdown for MediaWiki

Provides an services and classes for lockdown on permissions on single pages

**This code is meant to be executed within the MediaWiki application context. No standalone usage is intended.**

## Use in a MediaWiki extension

Add `"mwstake/mediawiki-component-lockdown": "~1.0"` to the `require` section of your `composer.json` file.

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
