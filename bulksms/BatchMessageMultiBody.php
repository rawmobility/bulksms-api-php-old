<?php
namespace Blender\Client;
use \DOMDocument;

require_once(dirname(__FILE__) . "/BatchMessage.php");
require_once(dirname(__FILE__) . "/BatchRecipientMultiBody.php");

class BatchMessageMultiBody extends BatchMessage {

    private $recipients = array();

    public function BatchMessageMultiBody() {

    }

    public function getRecipients() {
        return $this->recipients;
    }

    public function setRecipients($recipients) {
        $this->recipients = $recipients;

    }

    public function addRecipient4($recipient, $body, $reference, $routeId) {
        $this->addRecipient(null, $recipient, $body, $reference, $routeId);
    }

    public function addRecipient($originator, $recipient, $body, $reference = null, $routeId = null) {

        if (($originator == null) && ($this->getOriginator() == null))
            throw new Exception("You must set the Originator on either the Batch or the Recipient");

        if ($recipient == null)
            throw new Exception("You must set the Recipient number");

        if (($body == null) && ($this->getBody() == null))
            throw new Exception("You must set the Body on either the Batch or the Recipient");

        if (($routeId == null) && ($this->getRouteId() == null))
            throw new Exception("You must set the Route ID on either the Batch or the Recipient");

        $rec = new BatchRecipientMultiBody($originator, $recipient, $body, $reference, $routeId);

        $this->recipients[] = $rec;
    }

    public function addRecipient3($recipient, $body, $reference) {
        $this->addRecipient(null, $recipient, $body, $reference, null);
    }

    public function addRecipient2($recipient, $body) {
        $this->addRecipient(null, $recipient, $body, null, null);
    }

    public function addRecipient1($recipient) {
        $this->addRecipient(null, $recipient, null, null, null);
    }

    public function toXml() {
        $dom = new DOMDocument('1.0');
        $batch = parent::toXml('batchmulti');
        $msg = $dom->importNode($batch, true);

        foreach ($this as $key => $value) {
            if ($value == null)
                continue;

            if ($key == "recipients") {
                $recipients = $dom->createElement('recipients');
                foreach ($this->recipients as $r) {
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
$msg = new BatchMessageMultiBody();
$msg->setOriginator("asaf");
$msg->addRecipient1("61417188345");
$msg->addRecipient1("000000");

$dom = new DOMDocument('1.0');
$dom->formatOutput = true;
$node = $dom->importNode($msg->toXml(), true);
$dom->appendChild($node);
echo $dom->saveXml();
*/