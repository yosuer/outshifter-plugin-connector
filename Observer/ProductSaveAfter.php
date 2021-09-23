<?php
namespace Outshifter\Outshifter\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProductSaveAfter implements ObserverInterface
{

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(
      Logger $logger
    )
    {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $this->logger->info('[ProductSaveAfter] by product '.$id = $product->getId());
    }
}