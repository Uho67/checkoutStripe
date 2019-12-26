<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-12
 * Time: 12:09
 */

namespace Mytest\Checkout\Model\Source\NewPost;

use Magento\Framework\Option\ArrayInterface;
use Mytest\Checkout\Model\Commands\Area\GetListInterface;
use Mytest\Checkout\Model\AreaInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class Area
 * @package Mytest\Checkout\Model\Source\NewPost
 */
class Area implements ArrayInterface
{
    /**
     * @var GetListInterface
     */
    private $getList;

    /**
     * Area constructor.
     *
     * @param GetListInterface $getList
     */
    public function __construct(
        GetListInterface $getList
    ) {
        $this->getList = $getList;
    }

    /**
     * @return array
     * @throws NotFoundException
     */
    public function toOptionArray()
    {
        $items = $this->getList->execute();
        if(!$items || empty($items)) {
            throw new NotFoundException(__('Not found area new_post'));
        }
        $optionArray = array();
        foreach ($items as $item) {
            $optionArray[] = [
                'value' => $item[AreaInterface::AREA_REF],
                'label' => __($item[AreaInterface::AREA_NAME])
            ];
        }
        return $optionArray;
    }
}
