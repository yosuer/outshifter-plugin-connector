# Mage2 Module Outshifter PluginConnector

``outshifter/pluginconnector``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Outshifter Plugin Connector

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Outshifter`
 - Enable the module by running `php bin/magento module:enable Outshifter_PluginConnector`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Compile in dev mode `php bin/magento setup:di:compile`
 - Deploy in dev mode `php bin/magento setup:static-content:deploy`
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require outshifter/module-pluginconnector`
 - enable the module by running `php bin/magento module:enable Outshifter_PluginConnector`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications




## Attributes

 - Product - exported_outshifter (exported_outshifter)

