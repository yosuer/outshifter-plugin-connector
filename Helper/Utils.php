<?php

namespace Outshifter\Outshifter\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Store\Model\StoreManagerInterface;
use Magento\CatalogInventory\Api\StockStateInterface;

class Utils extends AbstractHelper
{
    /**
     * @var Configurable
     */
    protected $catalogProductTypeConfigurable;

    /**
     * @var StockStateInterface
     */
    protected $stockState;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;


    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        StockStateInterface $stockState,
        Configurable $catalogProductTypeConfigurable,
        StoreManagerInterface $storeManager)
    {
        $this->stockState = $stockState;
        $this->catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function hasParent($productId)
    {
        $parentByChild = $this->catalogProductTypeConfigurable->getParentIdsByChild($productId);
        return isset($parentByChild[0]);
    }

    public function getCurrencyStore()
    {
      return $this->storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    public function getQuantity($product)
    {
      return $this->stockState->getStockQty($product->getId(), $product->getStore()->getWebsiteId());
    }
}