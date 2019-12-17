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

/**
 * Class AbstractRefreshDataNewPost
 * @package Mytest\Checkout\Cron
 * this class used for refresh data in bd in tables for newPost
 */
abstract class AbstractRefreshDataNewPost
{
    /**
     * key for accept newPosta servises
     */
    const KEY_NEW_POST = 'f5b54b3f7ce5800ca0ffcd95a4dbed15';
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
     * AbstractRefreshDataNewPost constructor.
     *
     * @param ResourceConnectionFactory $resourceConnectionFactory
     * @param CurlFactory $curlFactory
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory,
        CurlFactory $curlFactory,
        JsonFactory $jsonFactory
    ) {
        $this->resourceConnectionFactory = $resourceConnectionFactory;
        $this->jsonFactory = $jsonFactory;
        $this->curlFactory = $curlFactory;
    }

    /**
     * refresh bd
     */
    abstract public function execute();
}
