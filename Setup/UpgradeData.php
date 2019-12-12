<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-11
 * Time: 13:04
 */

namespace Mytest\Checkout\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Mytest\Checkout\Cron\RefreshAreaNewPostFactory;
use Mytest\Checkout\Cron\RefreshCityNewPostFactory;

class UpgradeData implements UpgradeDataInterface
{
    private $refreshAreaFactory;
    private $refreshCityFactory;

    public function __construct(
        RefreshAreaNewPostFactory $refreshArea,
        RefreshCityNewPostFactory $refreshCity
    ) {
        $this->refreshAreaFactory = $refreshArea;
        $this->refreshCityFactory = $refreshCity;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.10', '<')) {
            $this->refreshCityFactory->create()->execute();
            $this->refreshAreaFactory->create()->execute();
        }
    }
}
