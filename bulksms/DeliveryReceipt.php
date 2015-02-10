<?php
namespace Blender\Client;
use \DOMDocument;

/**
 * Created by IntelliJ IDEA.
 * User: lettuce
 * Date: 4/02/15
 * Time: 11:14 AM
 */
class DeliveryReceipt {
    private $created;
    private $deliveryMessageId;
    private $status;
    private $clientReference;
    private $part;
    private $parts;

    function __construct($xml = null) {
        if($xml != null)
            $this->fromXml($xml);
    }

    /**
     * @return mixed
     */
    public function getClientReference() {
        return $this->clientReference;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getDeliveryMessageId() {
        return $this->deliveryMessageId;
    }

    /**
     * @return mixed
     */
    public function getPart() {
        return $this->part;
    }

    /**
     * @return mixed
     */
    public function getParts() {
        return $this->parts;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    public function fromXml($xmlString) {

        if (trim($xmlString) == false)
            throw new Exception("Missing XML Data");

        $vars = get_object_vars($this);
        $propnames = array_keys($vars);

        $doc = new DOMDocument();
        $doc->loadXML($xmlString);

        $receipts = $doc->getElementsByTagName("deliveryreceipt");

        foreach ($receipts as $node) {
            foreach ($node->childNodes as $child) {
                $prop = $child->nodeName;
                if (in_array($prop, $propnames)) {

                    $this->$prop = $child->nodeValue;
                }
            }
        }
    }
}