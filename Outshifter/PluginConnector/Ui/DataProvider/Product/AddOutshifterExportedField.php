<?php

namespace Outshifter\PluginConnector\Ui\DataProvider\Product;

use Magento\Ui\DataProvider\AddFieldToCollectionInterface;
use Magento\Framework\Data\Collection;

class AddOutshifterExportedField implements AddFieldToCollectionInterface
    {
        public function addField(Collection $collection, $field, $alias = null) {
            $collection->joinField(
                'outshifter_exported',
                'cataloginventory_outshifter_exported',
                'outshifter_exported',
                'product_id=entity_id',
                null,
                'left'
            );
        }
    }