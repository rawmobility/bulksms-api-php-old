<?php
namespace Blender\Client;
use \DOMDocument;

require_once(dirname(__FILE__) . "/RecipientType.php");

class BatchRecipientSingleBody {
    private $type;
    private $recipient;


    public function BatchRecipientSingleBody($type, $recipient) {
        $this->setType($type);
        $this->recipient = $recipient;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        switch ($type) {
            case RecipientType::CONTACT:
            case RecipientType::MAILINGLIST:
            case RecipientType::MSISDN:
                $this->type = $type;
                break;
            default:
                throw new Exception("Invalid recipient type: {$type}");
        }
    }

    public function getRecipient() {
        return $this->recipient;
    }

    public function setRecipient($recipient) {
        $this->recipient = $recipient;
    }

    public function toXml() {
        $dom = new DOMDocument('1.0');
        $recipient = $dom->createElement('recipient');
        foreach ($this as $key => $value) {
            if ($value == null)
                continue;

            $item = $dom->createElement($key, $value);
            $recipient->appendChild($item);
        }
        return $recipient;
    }

}

/*
$recipient = new BatchRecipientSingleBody(RecipientType::MSISDN, "61417188345");
$dom = new DOMDocument('1.0');
$node = $dom->importNode($recipient->toXml(), true);
$dom->appendChild($node);
echo $dom->saveXml();
*/