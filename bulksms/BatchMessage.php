<?php
namespace Blender\Client;
use \DOMDocument;

class BatchMessage  {

	// Message body
	private $body = null;

	// Message originator
	private $originator = null;

	// Route (specific)
	private $routeId = null;

	// When to process recipients
	private $processOnDelivery = false;

	// Whether to filter optouts
	private $filterOptouts = true;

	// Whether to filter optouts
	private $detailedResponse = true;

	// Message spread, in HOURS
	private $messageSpread = 0;

	public function getRouteId() {
		return $this->routeId;
	}

	public function setRouteId($routeId) {
		$this->routeId = $routeId;
	}

	public function isProcessOnDelivery() {
		return $this->processOnDelivery;
	}

	public function setProcessOnDelivery($processOnDelivery) {
		$this->processOnDelivery = $processOnDelivery;
	}


	public function getDeliveryScheduleDestinationTime() {

		return null;
	}

	public function isFilterOptouts() {
		return $this->filterOptouts;
	}

	public function setFilterOptouts($filterOptouts) {
		$this->filterOptouts = $filterOptouts;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function isDetailedResponse() {
		return $this->detailedResponse;
	}

	public function setDetailedResponse($detailedResponse) {
		$this->detailedResponse = $detailedResponse;
	}

	public function getBody() {
		return $this->body;
	}

	public function setBody($body) {
		$this->body = $body;
	}

	public function getOriginator() {
		return $this->originator;
	}

	public function setOriginator($originator) {
		$this->originator = $originator;
	}

	public function getCreatingUserId() {
		return $this->creatingUserId;
	}

	public function setCreatingUserId($creatingUserId) {
		$this->creatingUserId = $creatingUserId;
	}

	public function getMessageSpread() {
		return $this->messageSpread;
	}

	public function setMessageSpread($messageSpread) {
		$this->messageSpread = $messageSpread;
	}


    public function toXml($name) {
        $dom = new DOMDocument('1.0');
        $elem = $dom->createElement($name);
        foreach($this as $key => $value) {
            if($value == null)
                continue;

            $item = $dom->createElement($key, $value);
            $elem->appendChild($item);
        }
        return $elem;
    }
}
