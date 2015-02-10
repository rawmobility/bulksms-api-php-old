<?php
namespace Blender\Client;
use \DOMDocument;

class DeliveryMessage {
    private $created;
    private $id;
    private $body;
    private $inReplyTo;
    private $logicalMessageId;
    private $originator;
    private $recipient;

    function __construct($xml = null) {
        if($xml != null)
            $this->fromXml($xml);
    }


    /**
     * @return mixed
     */
    public function getBody() {
        return $this->body;
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
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getInReplyTo() {
        return $this->inReplyTo;
    }

    /**
     * @return mixed
     */
    public function getLogicalMessageId() {
        return $this->logicalMessageId;
    }

    /**
     * @return mixed
     */
    public function getOriginator() {
        return $this->originator;
    }

    /**
     * @return mixed
     */
    public function getRecipient() {
        return $this->recipient;
    }

    public function fromXml($xmlString) {

        if (trim($xmlString) == false)
            throw new Exception("Missing XML Data");

        $vars = get_object_vars($this);
        $propnames = array_keys($vars);

        $doc = new DOMDocument();
        $doc->loadXML($xmlString);

        $receipts = $doc->getElementsByTagName("deliverymessage");

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

