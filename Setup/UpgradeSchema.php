<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 10:39
 */

namespace Mytest\Checkout\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Mytest\Checkout\Model\CityInterface;
use Mytest\Checkout\Model\AreaInterface;

/**
 * Class UpgradeSchema
 * @package Mytest\Checkout\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.5', '<')) {
            $tableElevator = $setup->getConnection()->newTable(
                $setup->getTable(CityInterface::TABLE_NAME)
            )->addColumn(
                CityInterface::FIELD_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                    'identity' => true
                ],
                'Entity Id'
            )->addColumn(
                CityInterface::CITY_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'unsigned' => true,
                    'unique' => true
                ],
                'City Id'
            )->addColumn(
                CityInterface::CITY_NAME,
                Table::TYPE_TEXT,
                20,
                ['nullable' => false],
                'City name'
            )->addColumn(
                CityInterface::CITY_REF,
                Table::TYPE_TEXT,
                50,
                [
                    'nullable' => false,
                ],
                'city ref'
            )->addColumn(
                CityInterface::PARENT_AREA,
                Table::TYPE_TEXT,
                40,
                [
                    'nullable' => false
                ],
                'Area'
            )->setComment(
                'New post city'
            )->addIndex(
                $setup->getIdxName(
                    CityInterface::TABLE_NAME,
                    [
                        CityInterface::CITY_ID,
                        CityInterface::CITY_REF
                    ],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [
                    CityInterface::CITY_ID,
                    CityInterface::CITY_REF
                ],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            );
            $setup->getConnection()->createTable($tableElevator);
        }
        /**
         * table for area
         */
        if (version_compare($context->getVersion(), '1.0.7', '<')) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable(AreaInterface::TABLE_NAME)
            )->addColumn(
                AreaInterface::FIELD_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                    'identity' => true
                ],
                'Entity Id'
            )->addColumn(
                AreaInterface::AREA_NAME,
                Table::TYPE_TEXT,
                20,
                ['nullable' => false],
                'City name'
            )->addColumn(
                AreaInterface::AREA_REF,
                Table::TYPE_TEXT,
                50,
                [
                    'nullable' => false,
                ],
                'city ref'
            );
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
    }
}
