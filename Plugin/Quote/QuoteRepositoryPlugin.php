<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-06
 * Time: 15:51
 */

namespace Mytest\Checkout\Plugin\Quote;

use Mytest\Checkout\Model\NewPostAddressFactory;
use Mytest\Checkout\Api\NewPostAddressRepositoryInterface;

/**
 * Class QuoteRepositoryPlugin
 * @package Mytest\Checkout\Plugin\Quote
 */
class QuoteRepositoryPlugin
{
    private $modelFactory;
    private $newPostAddressReposotiry;

    /**
     * QuoteRepositoryPlugin constructor.
     *
     * @param NewPostAddressFactory $newPostAddressFactory
     * @param NewPostAddressRepositoryInterface $repository
     */
    public function __construct(
        NewPostAddressFactory $newPostAddressFactory,
        NewPostAddressRepositoryInterface $repository
    ) {
        $this->newPostAddressReposotiry = $repository;
        $this->modelFactory = $newPostAddressFactory;
    }

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     *
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function afterGet(\Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Quote\Api\Data\CartInterface $quote)
    {

        $newPostAddress = $this->newPostAddressReposotiry->getByQuoteId($quote->getId());
        if ($newPostAddress) {
            $extensionAttributes = $quote->getExtensionAttributes();
            if($extensionAttributes!=null) {
                $extensionAttributes->setNewPostAddress($newPostAddress);
                $quote->setExtensionAttributes($extensionAttributes);
            }
        }
        return $quote;
    }
}
