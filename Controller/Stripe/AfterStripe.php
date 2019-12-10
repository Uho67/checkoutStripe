<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-09
 * Time: 15:42
 */

namespace Mytest\Checkout\Controller\Stripe;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Serialize\Serializer\Json;

class AfterStripe extends Action
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $response = $this->getRequest()->getParams();
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}