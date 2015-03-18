<?php
namespace Blender\Client;
use \DOMDocument;

class BatchRecipientMultiBody  {
	private $originator;
	private $recipient;
	private $body;
	private $reference;
	private $routeId;

    function __construct($originator = null, $recipient = null, $body = null, $reference = null, $routeId = null) {
        $this->originator = $originator;
        $this->recipient = $recipient;
        $this->body = $body;
        $this->reference = $reference;
        $this->routeId = $routeId;
    }
    
	public function BatchRecipientMultiBody($originator = null, $recipient = null, $body = null, $reference = null, $routeId = null) {
		$this->originator = $originator;
		$this->recipient = $recipient;
		$this->body = $body;
		$this->reference = $reference;
		$this->routeId = $routeId;
	}

	public function getOriginator() {
		return $this->originator;
	}

	public function setOriginator($originator) {
		$this->originator = $originator;
	}

	public function getRecipient() {
		return $this->recipient;
	}

	public function setRecipient($recipient) {
		$this->recipient = $recipient;
	}

	public function getBody() {
		return $this->body;
	}

	public function setBody($body) {
		$this->body = $body;
	}

	public function getReference() {
		return $this->reference;
	}

	public function setReference($reference) {
		$this->reference = $reference;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getRouteId() {
		return $this->routeId;
	}

	public function setRouteId($routeId) {
		$this->routeId = $routeId;
	}

    public function toXml($name = "recipient") {
        $dom = new DOMDocument('1.0');
        $recipient = $dom->createElement($name);

        foreach($this as $key => $value) {
            if($value == null)
                continue;

            $item = $dom->createElement($key, $value);
            $recipient->appendChild($item);
        }
        return $recipient;
    }

}
/*
$recipient = new BatchRecipientMultiBody("orig", "61417188345", "body", "reference", "routeId");
$dom = new DOMDocument('1.0');
$node = $dom->importNode($recipient->toXml(), true);
$dom->appendChild($node);
echo $dom->saveXml();
*/