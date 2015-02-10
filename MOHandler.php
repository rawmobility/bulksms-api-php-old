<?php
require_once(dirname(__FILE__) . "/bulksms/DeliveryMessage.php");

$xml = $_POST['xml'];

// Test Data, use to simulate incoming message
/*
$xml = "<deliverymessage>
<created>2011-07-11T11:30:36+0000</created>
<id>0ed7b241-096e-4ca4-abc3-a365f11fae8f</id>
<body>Test 11:33</body>
<inReplyTo>cad1bf26-1294-4076-8b44-1197f2568104</inReplyTo>
<logicalMessageId>c8aca89b-84e1-42fe-a32c-5050cd6a0590</logicalMessageId>
<originator>44000009999</originator>
<recipient>44000000001</recipient>
</deliverymessage>";
*/

$incomingMessage = new Blender\Client\DeliveryMessage($xml);
//$incomingMessage->fromXml($xml);

echo "Message Received:\n";
echo "From:\t" . $incomingMessage->getOriginator() . "\n";
echo "To:\t" . $incomingMessage->getRecipient() . "\n";
echo "Body:\t" . $incomingMessage->getBody() . "\n";
echo "ID:\t" . $incomingMessage->getId() . "\n";