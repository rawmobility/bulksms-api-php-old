<?php
namespace Blender\Client;
use \DOMDocument;
use \DOMXPath;
use \Exception;

require_once(dirname(__FILE__) . "/BatchMessageMultiBody.php");
require_once(dirname(__FILE__) . "/BatchMessageSingleBody.php");
require_once(dirname(__FILE__) . "/UserRoutePricing.php");

class BulkSMS {
    CONST MSG_SINGLE = "single";
    CONST MSG_BATCH = "batch";
    private static $url = "https://apps.rawmobility.com/bulksms/xmlapi/";
    private static $USER_AGENT = "BlenderClient/1.0";
    private static $HTTP_TIMEOUT = 15;
    private $session = "";

    private static $routePricing = null;

    /**
     * Login to bulk system, get reusable session
     *
     * @param $username
     * @param $password
     */
    public function login($username, $password) {
        $url = BulkSMS::$url . "login/" . urldecode($username) . "/" . urlencode($password);
        $xml = $this->getRequest($url);
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $this->checkResponse($doc);

        $this->session = BulkSMS::xpathValue($doc, "/xaresponse/session");
    }

    /**
     * Get all routes/pricing
     * @return array of UserRoutePricing objects
     */
    public function getRoutePricing() {
        if(BulkSMS::$routePricing == null) {
            $pricings = array();
            $url = BulkSMS::$url . $this->session . "/entity/user.UserRoutePricing/SMS/visible";
            $xml = $this->getRequest($url);
            $doc = new DOMDocument();
            $doc->loadXML($xml);

            $xpath = new DOMXPath($doc);
            $query = "/xaresponse/entitylist/userroutepricing";
            $nodes = $xpath->query($query);

            foreach($nodes as $node) {
                $urp = new UserRoutePricing($node);
                $pricings[] = $urp;
            }

            BulkSMS::$routePricing = $pricings;
        }

        return BulkSMS::$routePricing;
    }

    /**
     * Get (first) Route pricing info by country name
     * @param $countryName
     * @return UserRoutePricing Object
     * @throws Exception if the country is not found
     */
    public function getRoutePricingByCountry($countryName) {
        $pricings = $this->getRoutePricing();

        foreach($pricings as $urp) {
           if($urp->getCountryName() == $countryName)
               return $urp;
        }
        throw new Exception("Unable to find route id for {$countryName}, please contact your reseller for additional coverage");
    }

    /**
     * Get (first) Route ID by country name
     * @param $countryName
     * @return Route ID (string)
     * @throws Exception if the country is not found
     */
    public function getRouteIdByCountry($countryName) {
        $pricings = BulkSMS::getRoutePricing();

        foreach($pricings as $urp) {
            if($urp->getCountryName() == $countryName)
                return $urp->getUserRouteId();
        }
        throw new Exception("Unable to find route id for {$countryName}, please contact your reseller for additional coverage");
    }

    public function getRequest($url) {
//        echo "URL: " . $url;
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, BulkSMS::$HTTP_TIMEOUT);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_USERAGENT, BulkSMS::$USER_AGENT);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

        /* Make the request and check the result. */
        $content = curl_exec($c);
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if ($errno = curl_errno($c)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}\n";
        }

        if ($status != 200)
            throw new Exception(sprintf('Unexpected HTTP return code %d\n' . $content, $status));


        return $content;
    }

    public static function checkResponse($doc) {
        $xpath = new DOMXPath($doc);
        $query = "/xaresponse/authentication/code";
        $code = BulkSMS::xpathValue($doc, $query);

        if ($code != "0") {
            $query = "/xaresponse/authentication/text";
            $text = BulkSMS::xpathValue($doc, $query);
            throw new Exception("Invalid Auth Responses: {$text} ({$code})");
        }

        return true;
    }

    public static function xpathValue($doc, $query, $default = null) {
        $xpath = new DOMXPath($doc);
        $nodes = $xpath->query($query);
        if ($nodes->length == 0)
            return $default;

        $node = $nodes->item(0);
        return $node->nodeValue;
    }

    public function sendSingle($originator, $recipient, $body, $routeId, $reference = null) {
        $recipient = new BatchRecipientMultiBody($originator, $recipient, $body, $reference, $routeId);
        $dom = new DOMDocument('1.0', "UTF-8");
        $node = $dom->importNode($recipient->toXml("message"), true);

        $dom->appendChild($node);
        $xml = $dom->saveXml();

        $result = $this->postXml(BulkSMS::MSG_SINGLE, $xml);

//        echo "Got result: {$result}\n";
        return $result;
    }

    public function postXml($type, $xml) {
        $url = BulkSMS::$url . $this->session . "/send/sms/" . $type;

//        echo "URL: {$url}\n";
//        echo "XML: {$xml}\n";
        $data = "xml=" . urlencode(trim($xml));

//        echo "DATA: {$data}";

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_TIMEOUT, BulkSMS::$HTTP_TIMEOUT);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_USERAGENT, BulkSMS::$USER_AGENT);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        if ($this->contains_any_multibyte($xml))
            curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));

        /* Make the request and check the result. */
        $content = curl_exec($c);
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if ($status != 200)
            throw new Exception(sprintf('Unexpected HTTP return code %d\n' . $content, $status));


        return $content;
    }

    function contains_any_multibyte($string) {
        return !mb_check_encoding($string, 'ASCII') && mb_check_encoding($string, 'UTF-8');
    }

    /**
     * Send message batch
     * @param $batch object of type BatchMessageSingleBody or BatchMessageMultiBody
     * @return XML string
     */
    public function sendBatch($batch) {
        $dom = new DOMDocument('1.0', "UTF-8");
        $node = $dom->importNode($batch->toXml(), true);

        $dom->appendChild($node);
        $xml = $dom->saveXml();

        $result = $this->postXml(BulkSMS::MSG_BATCH, $xml);

//        echo "Got result: {$result}\n";
        return $result;

    }
}
