<?php
namespace Blender\Client;
use \DOMDocument;

require_once(dirname(__FILE__) . "/BatchMessage.php");
require_once(dirname(__FILE__) . "/BatchRecipientSingleBody.php");

class BatchMessageSingleBody extends BatchMessage {
	
	// Whether to filter duplicates
	private $filterDuplicaets = true;

	private $recipients = array();

	public function BatchMessageSingleBody() {

	}


	public function getRecipients() {
		return $this->recipients;
	}

	public function setRecipients($recipients) {
		$this->recipients = $recipients;
	}

	public function isFilterDuplicaets() {
		return $this->filterDuplicaets;
	}

	public function setFilterDuplicaets($filterDuplicaets) {
		$this->filterDuplicaets = $filterDuplicaets;
	}
	
	public function addRecipient(RecipientType $type, $recipient) {
		$rec = new BatchRecipientSingleBody(type, recipient);
		$this->recipients[] = $rec;
	}
	
	public function addMSISDN($msisdn) {
		$rec = new BatchRecipientSingleBody(RecipientType::MSISDN, $msisdn);
		$this->recipients[] = $rec;
	}

	public function addMailingList($mailingListId) {
		$rec = new BatchRecipientSingleBody(RecipientType::MAILINGLIST, $mailingListId);
		$this->recipients[] = $rec;
	}

	public function addContact($contactId) {
		$rec = new BatchRecipientSingleBody(RecipientType::CONTACT, $contactId);
		$this->recipients[] = $rec;
	}

    public function toXml() {
        $dom = new DOMDocument('1.0');
        $batch = parent::toXml('batchsingle');
        $msg = $dom->importNode($batch, true);

        foreach($this as $key => $value) {
            if($value == null)
                continue;

            if($key == "recipients") {
                $recipients = $dom->createElement('recipients');
                foreach($this->recipients as $r) {
                    $node = $dom->importNode($r->toXml(), true);
                    $recipients->appendChild($node);
                }
                $msg->appendChild($recipients);
            } else {
                $item = $dom->createElement($key, $value);
                $msg->appendChild($item);
            }
        }
        return $msg;
    }
}

/*

$msg = new BatchMessageSingleBody();
$msg->setOriginator("asaf");
$msg->addMSISDN("61417188345");

$dom = new DOMDocument('1.0');
$dom->formatOutput = true;
$node = $dom->importNode($msg->toXml(), true);
$dom->appendChild($node);
echo $dom->saveXml();

*/