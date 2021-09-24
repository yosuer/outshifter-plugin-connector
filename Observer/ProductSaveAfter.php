<?php
namespace Outshifter\Outshifter\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Outshifter\Outshifter\Logger\Logger;

class ProductSaveAfter implements ObserverInterface
{

    /**
     * @var Logger
     */
    protected $_logger;

    public function __construct(Logger $logger)
    {
        $this->_logger = $logger;
    }
 
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $this->_logger->info('[ProductSaveAfter] by product '.$id = $product->getId());
    }
}