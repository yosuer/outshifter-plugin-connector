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

class RemoveInOutshifter extends Action
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
        Data $helper)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $productIds = $collection->getAllIds();
        $apiKey = $this->helper->getApiKey();
        foreach ($productIds as $productId)
        {
            $product = $this->productRepository->getById($productId);
            $product->setData('outshifter_exported', false);
            $this->productRepository->save($product);
            $this->messageManager->addSuccess(__('The product %1 will be deleted in outshifter', $productId));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('catalog/product/index');
    }
}