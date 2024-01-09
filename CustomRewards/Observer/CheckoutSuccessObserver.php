<?php
/**
 * Date :  1/7/24, 11:31 AM
 * Author : AHMED EHAB <sahmedehab@gmail.com>
 */
namespace Seoudi\CustomRewards\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Catalog\Model\ProductRepository;

class CheckoutSuccessObserver implements ObserverInterface
{
    protected $customerSession;
    protected $checkoutSession;
    protected $orderRepository;
    protected $customerFactory;
    protected $messageManager;
    protected $productRepository;

    public function __construct(
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        OrderRepositoryInterface $orderRepository,
        CustomerFactory $customerFactory,
        ManagerInterface $messageManager,
        ProductRepository $productRepository
    ) {
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->orderRepository = $orderRepository;
        $this->customerFactory = $customerFactory;
        $this->messageManager = $messageManager;
        $this->productRepository = $productRepository;
    }

    public function execute(Observer $observer)
    {
        // Retrieve order details
        $order = $observer->getEvent()->getOrder();

        // Ensure the order is in a paid state
        if ($order->getState() == \Magento\Sales\Model\Order::STATE_PROCESSING ||
            $order->getState() == \Magento\Sales\Model\Order::STATE_COMPLETE) {
            $customerId = $order->getCustomerId();
            $customer = $this->customerFactory->create()->load($customerId);
            if ($customer->getId()) {
                $rewardPointsSum = 0;
                foreach ($order->getAllVisibleItems() as $item) {
                    $sku = $item->getData('sku');
                    $product = $this->productRepository->get($sku); // Load product by SKU
                    $rewardPoints = $product->getData('reward_point');
                    $rewardPointsSum += $rewardPoints;
                }
                $currentPoints = $customer->getData('points_reward');
                $newPoints = $currentPoints + $rewardPointsSum;
                $customer->setData('points_reward', $newPoints);
                $customer->save();
                if ($newPoints <= 2) {
                    $this->messageManager->addNoticeMessage(__('You now have %1 point.', $newPoints));
                } else {
                    $this->messageManager->addSuccessMessage(__('You now have %1 points.', $newPoints));
                }
            }
        }
    }
}
