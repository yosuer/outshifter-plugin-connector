<?php

namespace Outshifter\Outshifter\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogInventory\Api\StockStateInterface;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Outshifter\Outshifter\Helper\Data;
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
     * @var ProductAttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var ProductFactory
     */
    protected $productLoader;

    /**
     * @var ProductRepositoryInterface
     */
    protected $storeManager;

    /**
     * @var Configurable
     */
    protected $catalogProductTypeConfigurable;

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
     * @param ProductAttributeRepositoryInterface $attributeRepository
     * @param StoreManagerInterface $storeManager
     * @param StockStateInterface $stockState
     * @param ProductFactory $productLoader
     * @param Data $helper
     * @param Logger $logger
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ProductRepositoryInterface $productRepository,
        ProductAttributeRepositoryInterface $attributeRepository,
        StoreManagerInterface $storeManager,
        StockStateInterface $stockState,
        ProductFactory $productLoader,
        Configurable $catalogProductTypeConfigurable,
        Data $helper,
        Logger $logger)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->storeManager = $storeManager;
        $this->stockState = $stockState;
        $this->productLoader = $productLoader;
        $this->catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
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
      $currency = $this->storeManager->getStore()->getCurrentCurrency()->getCode();
      if ($apiKey) {
        foreach ($productIds as $productId)
        {
            $parentByChild = $this->catalogProductTypeConfigurable->getParentIdsByChild($productId);
            if (isset($parentByChild[0])) {
              $this->_logger->info('[SendToOutshifter] skipping product '.$productId.', is a variant.');
            } else {
              $product = $this->productLoader->create()->load($productId);
              $productType = $product->getTypeId();
              if ($productType !== SendToOutshifter::SIMPLE && $productType !== SendToOutshifter::CONFIGURABLE) {
                $this->_logger->info('[SendToOutshifter] skipping product '.$productId.', is type '.$productType.'.');
              } else {
                $this->_logger->info('[SendToOutshifter] exporting product '.$productId.' (type = '.$productType.')');
                $quantity = $this->stockState->getStockQty($productId, $product->getStore()->getWebsiteId());
                $productImages = $product->getMediaGalleryImages();
                $images = array();
                foreach($productImages as $key => $image) {
                  $b64image = base64_encode(file_get_contents($image->getUrl()));
                  $images[] = array('order' => $key, "image" => 'data:image/jpg;base64,'.$b64image);
                }
                $optionsEnabled = $productType === SendToOutshifter::CONFIGURABLE;
                $variants = array();
                $options = array();
                if ($productType === SendToOutshifter::CONFIGURABLE) {
                    $available_variations = $product->getTypeInstance()->getUsedProducts($product);
                    $attributes = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
                    $quantity = 0;
                    $orderOption = 1;
                    foreach ($attributes as $attribute) {
                      $strOptions = '';
                      $this->_logger->info('[SendToOutshifter] ======= options ======');
                      foreach ($attribute['values'] as $option) {
                        $this->_logger->info('[SendToOutshifter] = option label '.$option['label']);
                        $this->_logger->info('[SendToOutshifter] = option value '.$option['value']);
                        $strOptions = $strOptions . (($strOptions == '') ? $option['label'] : ',' . $option['label']);
                      }
                      $options[] = array(
                        "name" => $attribute['label'],
                        "order" => $orderOption,
                        "values" => $strOptions
                      );
                      $orderOption++;
                    }
                    $this->_logger->info('[SendToOutshifter] ======= variantions ======');
                    foreach ($available_variations as $variation) {
                      $quantityVariant = $this->stockState->getStockQty($variation->getId(), $variation->getStore()->getWebsiteId());
                      $this->_logger->info('[SendToOutshifter] ====== variation SKU: '.$variation->getSku());
                      $this->_logger->info('[SendToOutshifter] price: '.$variation->getPrice());
                      $this->_logger->info('[SendToOutshifter] quantity: '.$quantityVariant);
                      $quantity = $quantity + $quantityVariant;
                      $title = '';
                      foreach ($attributes as $attribute) {
                        $this->_logger->info('[SendToOutshifter] === attrCode: '.$attribute['attribute_code']);
                        $optionId = $variation->getData($attribute['attribute_code']);
                        $this->_logger->info('[SendToOutshifter] optionId: '.$optionId);
                        if (null !== $optionId) {
                          $key = array_search($optionId, array_column($attribute['values'], 'value'));
                          $this->_logger->info('[SendToOutshifter] key: '.$key);
                          if ($key !== false) {
                            $value = $attribute['values'][$key]['label'];
                            $this->_logger->info('[SendToOutshifter] value: '.$value);
                            $title = $title === '' ? $value : $title.'-'.$value;
                          }
                        }
                      }
                      $variants[] = array(
                        "sku" => $variation->getSku(),
                        "price" => $variation->getPrice(),
                        "quantity" => $quantityVariant,
                        "title" => $title,
                        "barcode" => ""
                      );
                    }
                }

                $postData = array(
                  'title' => $product->getName(),
                  "description" => $product->getDescription(),
                  "publicPrice" => array(
                    "amount" => $product->getPrice(),
                    "currency" => $currency
                  ),
                  'origin' => 'MAGENTO',
                  'originId' => $productId,
                  "images" => $images,
                  "quantity" => $quantity,
                  "barcode" => "",
                  'sku' => $product->getSku(),
                  "optionsEnabled" => $optionsEnabled,
                  "options" => $options,
                  "variants" => $variants,
                  "weight" => $product->getWeight(),
                  "currency" => $currency
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
            }
        }
      } else {
          $this->messageManager->addError(__('You should config your outshifter api key in Stores -> Configuration -> Outshifter'));
      }
      $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
      return $resultRedirect->setPath('catalog/product/index');
    }
}