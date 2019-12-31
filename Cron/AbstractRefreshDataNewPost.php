<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-12
 * Time: 15:02
 */

namespace Mytest\Checkout\Cron;

use Magento\Framework\Serialize\Serializer\JsonFactory;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\App\ResourceConnectionFactory;
use Mytest\Checkout\Gateway\Config\ConfigFactory;

/**
 * Class AbstractRefreshDataNewPost
 * @package Mytest\Checkout\Cron
 * this class used for refresh data in bd in tables for newPost
 */
abstract class AbstractRefreshDataNewPost
{
    const PATH_KEY_NEW_POST = 'new_post_key';

    /**
     * @var CurlFactory
     */
    protected $curlFactory;
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;
    /**
     * @var ResourceConnectionFactory
     */
    protected $resourceConnectionFactory;
    /**
     * @var ConfigFactory
     */
    protected $configFactory;

    /**
     * AbstractRefreshDataNewPost constructor.
     *
     * @param ConfigFactory $configFactory
     * @param ResourceConnectionFactory $resourceConnectionFactory
     * @param CurlFactory $curlFactory
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        ConfigFactory $configFactory,
        ResourceConnectionFactory $resourceConnectionFactory,
        CurlFactory $curlFactory,
        JsonFactory $jsonFactory
    ) {
        $this->configFactory = $configFactory;
        $this->resourceConnectionFactory = $resourceConnectionFactory;
        $this->jsonFactory = $jsonFactory;
        $this->curlFactory = $curlFactory;
    }

    /**
     * refresh bd
     */
    abstract public function execute();

    /**
     * @return mixed
     */
    protected function getNewPostKey()
    {
        return $this->configFactory->create()->getNewPostValue(self::PATH_KEY_NEW_POST);
    }
}
