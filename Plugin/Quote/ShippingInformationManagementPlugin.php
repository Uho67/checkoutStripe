<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-08
 * Time: 09:11
 */

namespace Mytest\Checkout\Plugin\Quote;

use Mytest\Checkout\Api\NewPostAddressRepositoryInterface;

class ShippingInformationManagementPlugin
{
    private $newPostAddressRepository;
    public function __construct(NewPostAddressRepositoryInterface $newPostAddressRepository)
    {
        $this->newPostAddressRepository = $newPostAddressRepository;
    }

    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $object,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    )
    {
        $newPostAddress = $addressInformation->getExtensionAttributes()->getNewPostAddress();
        if($newPostAddress) {
            $newPostAddress->setQuoteId($cartId);
            $this->newPostAddressRepository->save($newPostAddress);
        }
    }
}