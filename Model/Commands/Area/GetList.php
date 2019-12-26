<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-26
 * Time: 12:17
 */

namespace Mytest\Checkout\Model\Commands\Area;

use Magento\Framework\App\ResourceConnection;
use Mytest\Checkout\Model\AreaInterface;

/**
 * Class GetList
 * @package Mytest\Checkout\Model\Commands\Area
 */
class GetList implements GetListInterface
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * GetList constructor.
     *
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * @return array|mixed
     */
    public function execute()
    {
        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName(AreaInterface::TABLE_NAME);
        return $connection->fetchAll($connection->select()->from($tableName));
    }
}
