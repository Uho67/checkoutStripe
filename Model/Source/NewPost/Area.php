<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-12
 * Time: 12:09
 */

namespace Mytest\Checkout\Model\Source\NewPost;

use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NotFoundException;
use Mytest\Checkout\Model\AreaInterface;

class Area implements ArrayInterface
{
    private $resource;
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    public function toOptionArray()
    {
        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName(AreaInterface::TABLE_NAME);
        $items = $connection->fetchAll($connection->select()->from($tableName));
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