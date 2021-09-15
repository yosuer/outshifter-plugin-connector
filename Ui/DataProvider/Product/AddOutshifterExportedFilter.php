<?php

namespace Outshifter\PluginConnector\Ui\DataProvider\Product;

use Magento\Ui\DataProvider\AddFilterToCollectionInterface;
use Magento\Framework\Data\Collection;

class AddOutshifterExportedFilter implements AddFilterToCollectionInterface 
{ 
    public function addFilter(Collection $collection, $field, $condition = null) 
    { 
        if (isset($condition['eq'])) { 
            $collection->addFieldToFilter($field, $condition); 
        } 
    } 
}