# Mage2 Module Outshifter Connector

``outshifter/connector``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Outshifter Connector

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Outshifter`
 - Enable the module by running `php bin/magento module:enable outshifter/connector`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Compile in dev mode `php bin/magento setup:di:compile`
 - Deploy in dev mode `php bin/magento setup:static-content:deploy`
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

```
composer require outshifter/connector
php bin/magento maintenance:enable
php bin/magento module:enable Outshifter_Outshifter
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento maintenance:disable
php bin/magento cache:flush
```

## To Remove

```
composer require outshifter/connector
php bin/magento maintenance:enable
php bin/magento module:enable Outshifter_Outshifter
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento maintenance:disable
php bin/magento cache:flush

rm -rf var/cache/ var/generation/ var/page_cache/ var/view_preprocessed/ var/di/ generated/* var/generation/*
sudo chown -R . bitnami:daemon

```

## Specifications




## Attributes

 - Product - exported_outshifter (exported_outshifter)

