<?php

namespace Outshifter\Outshifter\Model;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\QuoteManagement;
use Outshifter\Outshifter\Helper\Utils;
use Outshifter\Outshifter\Logger\Logger;

class OutshifterApiServiceImpl
{

  /**
   * @var StoreManagerInterface
   */
  protected $storeManager;

  /**
   * @var CustomerFactory
   */
  protected $customerFactory;

  /**
   * @var QuoteFactory
   */
  protected $quoteFactory;

  /**
   * @var Product
   */
  protected $productModel;

  /**
   * @var CustomerRepositoryInterface
   */
  protected $customerRepository;

  /**
   * @var QuoteManagement
   */
  protected $quoteManagement;

  /**
   * @var Utils
   */
  protected $utils;

  /**
   * @var Logger
   */
  protected $_logger;

  public function __construct(
    StoreManagerInterface $storeManager,
    CustomerFactory $customerFactory,
    QuoteFactory $quoteFactory,
    CustomerRepositoryInterface $customerRepository,
    Product $productModel,
    QuoteManagement $quoteManagement,
    Utils $utils,
    Logger $logger
  ) {
    $this->storeManager = $storeManager;
    $this->customerFactory = $customerFactory;
    $this->quoteFactory = $quoteFactory;
    $this->customerRepository = $customerRepository;
    $this->productModel = $productModel;
    $this->quoteManagement = $quoteManagement;
    $this->utils = $utils;
    $this->_logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function saveOrder()
  {
    $orderData = [
      'email'        => 'test@webkul.com', //buyer email id
      'shipping_address' => [
        'firstname'    => 'jhon', //address Details
        'lastname'     => 'Deo',
        'street' => 'aaaa',
        'city' => 'Oslo',
        'country_id' => 'NO',
        'region' => 'Oslo',
        'postcode' => '43244',
        'telephone' => '52332',
        'fax' => '32423',
        'save_in_address_book' => 0
      ]
    ];
    $store = $this->storeManager->getStore();
    $websiteId = $this->storeManager->getStore()->getWebsiteId();
    $customer = $this->customerFactory->create();
    $customer->setWebsiteId($websiteId)
      ->setStore($store)
      ->setFirstname('Nombre')
      ->setLastname('Apellido')
      ->setEmail('test@aaaa.com')
      ->setPassword('password');
    $customer->save();
    $quote = $this->quoteFactory->create();
    $quote->setStore($store);
    $customer = $this->customerRepository->getById($customer->getEntityId());
    $quote->setCurrency();
    $quote->assignCustomer($customer);

    $product = $this->productModel->load(1);
    $quote->addProduct(
      $product,
      intval(1)
    );

    $quote->getBillingAddress()->addData($orderData['shipping_address']);
    $quote->getShippingAddress()->addData($orderData['shipping_address']);
    $shippingAddress = $quote->getShippingAddress();
    $shippingAddress->setCollectShippingRates(true)
      ->collectShippingRates()
      ->setShippingMethod('freeshipping_freeshipping');
    $quote->setPaymentMethod('checkmo'); //payment method
    $quote->setInventoryProcessed(false); //not effetc inventory
    $quote->save(); //Now Save quote and your quote is ready

    // Set Sales Order Payment
    $quote->getPayment()->importData(['method' => 'checkmo']);

    // Collect Totals & Save Quote
    $quote->collectTotals()->save();

    // Create Order From Quote
    $order = $this->quoteManagement->submit($quote);
    $order->setEmailSent(0);
    if ($order->getEntityId()) {
      $result = $order->getRealOrderId();
    } else {
      $result = 'Error';
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrency()
  {
    try {
      $response = $this->utils->getCurrencyStore();
    } catch (\Exception $e) {
      $response = $e->getMessage();
    }

    return $response;
  }
}
