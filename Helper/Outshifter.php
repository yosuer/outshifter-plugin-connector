<?php

namespace Outshifter\Outshifter\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Outshifter\Outshifter\Logger\Logger;

class Outshifter extends AbstractHelper
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        Logger $logger)
    {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function saveProduct()
    {
        
    }
}