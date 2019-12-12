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

abstract class AbstractRefreshDataNewPost
{
    const KEY_NEW_POST = 'f5b54b3f7ce5800ca0ffcd95a4dbed15';

    protected $curlFactory;
    protected $jsonFactory;
    protected $resourceConnectionFactory;

    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory,
        CurlFactory $curlFactory,
        JsonFactory $jsonFactory
    ) {
        $this->resourceConnectionFactory = $resourceConnectionFactory;
        $this->jsonFactory = $jsonFactory;
        $this->curlFactory = $curlFactory;
    }

    abstract public function execute();
}