![AWHS Logo](https://nicolasmeloni.ovh/images/awhspanel.png)

# CoreBundle
CoreBundle of AWHSPanel

This package provides various scripts to manage multiple servers and provides a shop.

## Requirements
* Have installed the [foundation](https://github.com/TheGrimmChester/AWHSPanel/blob/master/README.md)  
* Have installed the [UserBundle](https://github.com/TheGrimmChester/UserBundle/blob/master/README.md)

## Installation
1. Clone this bundle inside `src/AWHS/` directory.
2. Enable the bundle in the kernel by adding the following line right after `//AWHS Bundles` in `app/AppKernel.php` file:  
`new AWHS\CoreBundle\AWHSCoreBundle(),`
3. Append the following configuration to the `app/routing.yml` file:  
```yaml
awhs_core:
    resource: "@AWHSCoreBundle/Resources/config/routing.yml"
    prefix:   /
```
4. Update database & clear cache: `php bin/console doctrine:schema:update --force; php bin/console cache:clear; php bin/console cache:clear --env=prod`  
You may have to set permissions back to www-data `chown -R www-data:www-data /usr/local/awhspanel/panel/*`
5. Load data fixtures: `php bin/console doctrine:fixtures:load --fixtures=src/AWHS/CoreBundle/DataFixtures/ORM --append`

## TODO
- [ ] Multilingual
- [ ] Refactoring old code.
