<?php

namespace Outshifter\Outshifter\Model;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\QuoteManagement;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
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
   * @var CartRepositoryInterface
   */
  protected $cartRepository;

  /**
   * @var CartManagementInterface
   */
  protected $cartManagement;

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
    CartRepositoryInterface $cartRepository,
    CartManagementInterface $cartManagement,
    Utils $utils,
    Logger $logger
  ) {
    $this->storeManager = $storeManager;
    $this->customerFactory = $customerFactory;
    $this->quoteFactory = $quoteFactory;
    $this->customerRepository = $customerRepository;
    $this->productModel = $productModel;
    $this->quoteManagement = $quoteManagement;
    $this->cartRepository = $cartRepository;
    $this->cartManagement = $cartManagement;
    $this->utils = $utils;
    $this->_logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function saveOrder()
  {
    $orderData = [
      'email'        => 'test@iqplus.com', //buyer email id
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
    $customer->setWebsiteId($websiteId);
    $customer->loadByEmail($orderData['email']); // load customet by email address
    if (!$customer->getEntityId()) {
      //If not avilable then create this customer 
      $customer->setWebsiteId($websiteId)
        ->setStore($store)
        ->setFirstname($orderData['shipping_address']['firstname'])
        ->setLastname($orderData['shipping_address']['lastname'])
        ->setEmail($orderData['email'])
        ->setPassword($orderData['email']);
      $customer->save();
    }

    $this->_logger->info('[OutshifterApi.saveOrder] Customer created');

    $cartId = $this->cartManagement->createEmptyCart();
    $cart = $this->cartRepository->get($cartId);
    $cart->setStore($store);
    $customer = $this->customerRepository->getById($customer->getEntityId());
    $cart->setCurrency();
    $cart->assignCustomer($customer);

    $product = $this->productModel->load(1);
    $cart->addProduct(
      $product,
      intval(1)
    );

    $this->_logger->info('[OutshifterApi.saveOrder] Product added');

    $cart->getBillingAddress()->addData($orderData['shipping_address']);
    $cart->getShippingAddress()->addData($orderData['shipping_address']);
    $cart->getShippingAddress()->setCollectShippingRates(true);
    $cart->getShippingAddress()->setShippingMethod('freeshipping_freeshipping');

    $this->_logger->info('[OutshifterApi.saveOrder] Shipping method setted');

    $cart->setPaymentMethod('checkmo'); //payment method
    $cart->setInventoryProcessed(false); //not effetc inventory

    $this->_logger->info('[OutshifterApi.saveOrder] Payment method setted');

    // Set Sales Order Payment
    $cart->getPayment()->importData(['method' => 'checkmo']);

    // Collect Totals & Save Quote
    $cart->collectTotals();

    $this->_logger->info('[OutshifterApi.saveOrder] Cart prepared');

    // Create Order From Quote
    $cart->save();

    $cart = $this->cartRepository->get($cart->getId());

    $this->_logger->info('[OutshifterApi.saveOrder] Cart ' . $cart->getId() . ' saved');

    $cartCustomer = $cart->getCustomer();

    $this->_logger->info('[OutshifterApi.saveOrder] CartCustomer: ' . implode('; ', $cartCustomer));

    $orderId = $this->cartManagement->placeOrder($cart->getId());

    $this->_logger->info('[OutshifterApi.saveOrder] Order ' . $orderId . ' created');

    return $orderId;
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
