<?php
/**
 * Date :  1/7/24, 11:31 AM
 * Author : AHMED EHAB <sahmedehab@gmail.com>
 */
namespace Seoudi\CustomRewards\Controller\Share;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Message\ManagerInterface;

class Success extends Action
{
    protected $resultJsonFactory;
    protected $customerSession;
    protected $messageManager;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CustomerSession $customerSession,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        try {
            $productId = $this->getRequest()->getPost('product_id');
            $success = true;
            $customerId = $this->customerSession->getCustomerId();
            if ($customerId) {
                $customer = $this->customerSession->getCustomer();
                $currentPoints = $customer->getData('points_reward');
                $newPoints = $currentPoints + 2;
                $customer->setData('points_reward', $newPoints)->save();
                $this->customerSession->setCustomer($customer);
                $this->messageManager->addSuccessMessage(__('Thank you for sharing! You now have %1 points in total.', $newPoints));
                return $result->setData(['success' => $success]);
            } else {
                throw new \Exception('Customer not logged in.');
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $result->setData(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
