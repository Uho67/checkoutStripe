<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2020-01-08
 * Time: 09:11
 */

namespace Mytest\Checkout\Plugin\Quote;

use Magento\Checkout\Model\ShippingInformationManagement;
use Mytest\Checkout\Api\NewPostAddressRepositoryInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class ShippingInformationManagementPlugin
 * @package Mytest\Checkout\Plugin\Quote
 */
class ShippingInformationManagementPlugin
{
    /**
     * @var NewPostAddressRepositoryInterface
     */
    private $newPostAddressRepository;

    public function __construct(NewPostAddressRepositoryInterface $newPostAddressRepository)
    {
        $this->newPostAddressRepository = $newPostAddressRepository;
    }

    /**
     * @param ShippingInformationManagement $object
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     *
     * @throws CouldNotSaveException
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagement $object,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $newPostAddress = $addressInformation->getExtensionAttributes()->getNewPostAddress();
        if ($newPostAddress) {
            $newPostAddress->setQuoteId($cartId);
            $this->newPostAddressRepository->save($newPostAddress);
        }
    }
}
