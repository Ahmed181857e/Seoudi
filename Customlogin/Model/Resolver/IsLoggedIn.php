<?php
/**
 * Date :  1/7/24, 11:31 AM
 * Author : AHMED EHAB <sahmedehab@gmail.com>
 */

namespace Seoudi\Customlogin\Model\Resolver;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class IsLoggedIn implements ResolverInterface
{
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @param CustomerSession $customerSession
     */
    public function __construct(CustomerSession $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        return $this->customerSession->isLoggedIn();
    }
}
