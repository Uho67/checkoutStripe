<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-24
 * Time: 12:34
 */

namespace Mytest\Checkout\Gateway\Config;

use Magento\Payment\Gateway\ConfigInterface;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config implements ConfigInterface
{
    const DEFAULT_PATTERN = 'payment/%s/%s';
    const PATH_PATTERN_SHIPPING = 'carriers/vaimo_stripe_newpost/%s';
    const PATH_PATTERN_PAYMENT  = 'payment/mytest_stripe/%s';
    private $methodCode;
    private $pathPattern;
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getValue($field, $storeId = null)
    {
        if ($this->methodCode === null || $this->pathPattern === null) {
            return null;
        }

        return $this->scopeConfig->getValue(
            sprintf($this->pathPattern, $this->methodCode, $field),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    public function setMethodCode($methodCode)
    {
        $this->methodCode = $methodCode;
    }
    public function setPathPattern($pathPattern)
    {
        $this->pathPattern = $pathPattern;
    }
    public function getStripeValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            sprintf(self::PATH_PATTERN_PAYMENT, $field),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    public function getNewPostValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            sprintf(self::PATH_PATTERN_SHIPPING, $field),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}