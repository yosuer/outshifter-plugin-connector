<?php

namespace Outshifter\Outshifter\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ProductFactory;
use Outshifter\Outshifter\Helper\Data;
use Outshifter\Outshifter\Helper\Utils;
use Outshifter\Outshifter\Helper\OutshifterService;
use Outshifter\Outshifter\Logger\Logger;

class SendToOutshifter extends Action
{

    const SIMPLE = 'simple';
    const CONFIGURABLE = 'configurable';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ProductFactory
     */
    protected $productLoader;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Utils
     */
    protected $utils;

    /**
     * @var OutshifterService
     */
    protected $outshifterService;

    /**
     * @var Logger
     */
    protected $_logger;


    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ProductRepositoryInterface $productRepository,
        ProductFactory $productLoader,
        OutshifterService $outshifterService,
        Data $helper,
        Utils $utils,
        Logger $logger)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
        $this->productLoader = $productLoader;
        $this->outshifterService = $outshifterService;
        $this->helper = $helper;
        $this->utils = $utils;
        $this->_logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
      $collection = $this->filter->getCollection($this->collectionFactory->create());
      $productIds = $collection->getAllIds();
      $this->_logger->info('[SendToOutshifter] init by '.implode(",", $productIds));
      $apiKey = $this->helper->getApiKey();
      $currency = $this->utils->getCurrencyStore();
      if ($apiKey) {
        foreach ($productIds as $productId)
        {
            $hasParent = $this->utils->hasParent($productId);
            if ($hasParent) {
              $this->_logger->info('[SendToOutshifter] skipping product '.$productId.', is a variant.');
            } else {
              $product = $this->productLoader->create()->load($productId);
              $productType = $product->getTypeId();
              if ($productType !== SendToOutshifter::SIMPLE && $productType !== SendToOutshifter::CONFIGURABLE) {
                $this->_logger->info('[SendToOutshifter] skipping product '.$productId.', is type '.$productType.'.');
              } else {
                $result = $this->outshifterService->saveProduct($product, $apiKey, $currency);
                if ($result['success']) {
                  $product->setData('outshifter_exported', true);
                  $this->productRepository->save($product);
                  $this->messageManager->addSuccess(__('The product %1 was exported to outshifter', $productId));
                } else {
                  $this->messageManager->addError(__($result['message'], $productId));
                }
              }
            }
        }
      } else {
          $this->messageManager->addError(__('You should config your outshifter api key in Stores -> Configuration -> Outshifter'));
      }
      $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
      return $resultRedirect->setPath('catalog/product/index');
    }
}