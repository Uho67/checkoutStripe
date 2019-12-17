<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-06
 * Time: 15:39
 */

namespace Mytest\Checkout\Gateway\Command;

/**
 * Interface AuthorizationInterface
 * @package Mytest\Checkout\Gateway\Command
 */
interface AuthorizationInterface
{
    /**
     * @return string
     */
    public function execute();
}
