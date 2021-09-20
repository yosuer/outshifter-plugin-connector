<?php

namespace Outshifter\Outshifter\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Backend\Model\View\Result\Redirect;
use Outshifter\Outshifter\Helper\Data;
use Outshifter\Outshifter\Logger\Logger;

class SendToOutshifter extends Action
{

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
     * @var Data
     */
    protected $helper;

    /**
     * @var Logger
     */
    protected $_logger;


    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ProductRepositoryInterface $productRepository
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ProductRepositoryInterface $productRepository,
        Data $helper,
        Logger $logger)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
        $this->helper = $helper;
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
      if ($apiKey) {
          foreach ($productIds as $productId)
          {
              $product = $this->productRepository->getById($productId);

              $postData = array(
                'title' => $product->getName(),
                'origin' => 'MAGENTO',
                'originId' => $productId,
                'sku' => $product->getSku()
              );

              $ch = curl_init('https://03d1-186-22-17-73.ngrok.io/magento/products');
              curl_setopt_array($ch, array(
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'authorization: '.$apiKey,
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => json_encode($postData)
              ));
              $response = curl_exec($ch);
              if($response === FALSE) {
                $this->messageManager->addError(__('Connection problem exporting product %1, try again in a moment', $productId));
                die(curl_error($ch));
              }
              if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 401) {
                $this->messageManager->addError(__('Please review your outshifter api key in Stores -> Configuration -> Outshifter'));
                break;
              }

              curl_close($ch);

              $product->setData('outshifter_exported', true);
              $this->productRepository->save($product);
              $this->messageManager->addSuccess(__('The product %1 was exported to outshifter', $productId));
          }
      } else {
          $this->messageManager->addError(__('You should config your outshifter api key in Stores -> Configuration -> Outshifter'));
      }
      $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
      return $resultRedirect->setPath('catalog/product/index');
    }
}