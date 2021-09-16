<?php

namespace Outshifter\Outshifter\Ui\DataProvider\Product;

use Magento\Ui\DataProvider\AddFilterToCollectionInterface;
use Magento\Framework\Data\Collection;

class AddOutshifterFilter implements AddFilterToCollectionInterface 
{ 
    public function addFilter(Collection $collection, $field, $condition = null) 
    { 
        if (isset($condition['eq'])) { 
            $collection->addFieldToFilter($field, $condition); 
        } 
    } 
}