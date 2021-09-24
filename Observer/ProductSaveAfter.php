<?php
namespace Outshifter\Outshifter\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Outshifter\Outshifter\Logger\Logger;
use Outshifter\Outshifter\Helper\OutshifterService;

class ProductSaveAfter implements ObserverInterface
{

    /**
     * @var Logger
     */
    protected $_logger;

    /**
     * @var OutshifterService
     */
    protected $outshifterService;

    public function __construct(
        OutshifterService $outshifterService,
        Logger $logger)
    {
        $this->outshifterService = $outshifterService;
        $this->_logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $id = $product->getId();
        $outshifterExported = $product->getData('outshifter_exported');
        $this->_logger->info('[ProductSaveAfter] by product '.$id.', outshifter_exported='.$outshifterExported);
        if ($outshifterExported == 1) {
          $this->outshifterService->saveProduct($product);
        }
    }
}