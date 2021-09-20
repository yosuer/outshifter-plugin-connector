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
      $this->_logger->info('I did something');
      $collection = $this->filter->getCollection($this->collectionFactory->create());
      $productIds = $collection->getAllIds();
      $apiKey = $this->helper->getApiKey();
      if ($apiKey) {
          foreach ($productIds as $productId)
          {
              $productDataObject = $this->productRepository->getById($productId);

              $postData = array(
                'title' => 'A new product'
              );

              $ch = curl_init('https://03d1-186-22-17-73.ngrok.io/api/products');
              curl_setopt_array($ch, array(
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: '.$apiKey,
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => json_encode($postData)
              ));
              $response = curl_exec($ch);
              if($response === FALSE) {
                $this->messageManager->addError(__('Connection problem, try again in a moment'));
                die(curl_error($ch));
              }
              if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 401) {
                $this->messageManager->addError(__('Please review your outshifter api key in Store -> Settings -> Outshifter'));
                die();
              }

              curl_close($ch);

              $productDataObject->setData('outshifter_exported', true);
              $this->productRepository->save($productDataObject);
          }
          $this->messageManager->addSuccess(__('A total of %1 product(s) have been exported to outsfhiter******.', count($productIds)));
      } else {
          $this->messageManager->addError(__('You should config your outshifter api key in Store -> Settings -> Outshifter'));
      }
      $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
      return $resultRedirect->setPath('catalog/product/index');
    }
}