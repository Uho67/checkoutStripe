<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 2019-12-23
 * Time: 16:30
 */

namespace Mytest\Checkout\Model\Carrier;

use Psr\Log\LoggerInterface;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Framework\Serialize\Serializer\JsonFactory;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class NewPost
 * @package Mytest\Checkout\Model\Carrier
 */
class NewPost extends AbstractCarrier implements CarrierInterface
{
    const PATH_KEY_NEW_POST = 'new_post_key';
    const URL_NEW_POST = 'https://api.novaposhta.ua/v2.0/json/';
    protected $_code = 'vaimo_stripe_newpost';
    /**
     * @var CurlFactory
     */
    private $curlFactory;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var Curl
     */
    private $curl;
    /**
     * @var Json
     */
    private $json;
    /**
     * @var DateTime
     */
    private $dateTime;
    /**
     * @var ResultFactory
     */
    protected $_rateResultFactory;
    /**
     * @var MethodFactory
     */
    protected $_rateMethodFactory;

   private $timeZona;
    public function __construct(
        TimezoneInterface $timezone,
        DateTime $dateTime,
        JsonFactory $jsonFactory,
        CurlFactory $curlFactory,
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->timeZona = $timezone;
        $this->dateTime = $dateTime;
        $this->jsonFactory = $jsonFactory;
        $this->curlFactory = $curlFactory;
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * get allowed methods
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * @param $currencyCode
     *
     * @return bool
     */
    private function getExchangeRates($currencyCode)
    {
        try {
            $curl = $this->getCurl();
            $json = $this->getJson();
            $date = $this->dateTime->gmtDate('d.m.Y ');
            $curl->get('https://api.privatbank.ua/p24api/exchange_rates?json&date=' . $date);
            $request = $json->unserialize($curl->getBody());
            /**
             * if today's exchange is not , made request yesterday's exchange
             */
            if (empty($request['exchangeRate'])) {
                $date = $this->timeZona->date(strtotime($date . "-1 days"))->format('d.m.Y');
                $curl->get('https://api.privatbank.ua/p24api/exchange_rates?json&date=' . $date);
                $request = $json->unserialize($curl->getBody());
            }
            for ($i = 1; $i < count($request['exchangeRate']); $i++) {
                if ($request['exchangeRate'][$i]['currency'] == $currencyCode) {
                    return $request['exchangeRate'][$i];
                }
            }
        } catch (\Exception $exception) {
            return false;
        }

        return false;
    }

    /**
     * @param RateRequest $request
     *
     * @return float|int
     * @throws NotFoundException
     */
    private function getShippingPrice(RateRequest $request)
    {
        //count general price
        $generalPrice = 0;
        foreach ($request->getAllItems() as $item) {
            $generalPrice += $item->getQty() * $item->getPrice();
        }
        if ($generalPrice == 0) {
            return 0;
        }
        try {
            $exchangeRates = $this->getExchangeRates($request->getBaseCurrency()->getCurrencyCode());
            if(!$exchangeRates) {
                $this->_logger->log(100,$request->getBaseCurrency()->getCurrencyCode()."not available in  Exchange Rates");
                throw new NotFoundException(__('Your currency not available'));
            }
            $curl = $this->getCurl();
            $json = $this->getJson();
            $curl->setHeaders([
                'Content-Type' => 'application/json'
            ]);
            if(is_array($request->getAllItems()[0]->getAddress()->getExtensionAttributes())){
                $cityRef = $request->getAllItems()[0]->getAddress()->getExtensionAttributes('new_post_address')['new_post_address']['city_ref'];
            } else {
                $cityRef = $request->getAllItems()[0]->getQuote()->getExtensionAttributes()->getNewPostAddress()->getCityRef();
            }
            $param = $json->serialize([
                'modelName' => 'InternetDocument',
                'calledMethod' => 'getDocumentPrice',
                'apiKey' => $this->getConfigData(self::PATH_KEY_NEW_POST),
                'methodProperties' => [
                    "CitySender" => "db5c88e0-391c-11dd-90d9-001a92567626",
//                    "CityRecipient" => $request->getData('dest_region_code'),
                    "CityRecipient" => $cityRef,
                    "Weight" => $request->getData('weight') || 1000,
                    "ServiceType" => "WarehouseWarehouse",
                    "Cost" => $generalPrice * $exchangeRates['saleRate'],
                    "CargoType" => "Cargo",
                    "SeatsAmount" => "1",
                    "PackCount" => "1",
                    "PackRef" => "1499fa4a-d26e-11e1-95e4-0026b97ed48a"
                ]
            ]);
            $curl->post(self::URL_NEW_POST, $param);
            $answer = $json->unserialize($curl->getBody());
            if($answer['success'] === false) {
                return 0;
            }
            return $answer['data'][0]['Cost'] / $exchangeRates['saleRate'];
        } catch (NotFoundException $exception) {
            throw new NotFoundException(__($exception));
        }
    }

    /**
     * @param RateRequest $request
     *
     * @return bool|\Magento\Framework\DataObject|Result|null
     * @throws NotFoundException
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));
        $amount = $this->getShippingPrice($request);
        $method->setPrice($amount);
        $method->setCost($amount);
        $result->append($method);

        return $result;
    }

    /**
     * @return Curl
     */
    private function getCurl()
    {
        if ($this->curl == null) {
            $this->curl = $this->curlFactory->create();
        }

        return $this->curl;
    }

    /**
     * @return Json
     */
    private function getJson()
    {
        if ($this->json == null) {
            $this->json = $this->jsonFactory->create();
        }

        return $this->json;
    }
}
