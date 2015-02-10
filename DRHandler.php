<?php
require_once(dirname(__FILE__) . "/bulksms/DeliveryReceipt.php");

$xml = $_POST['xml'];

// Test Data, use to simulate incoming message
/*
$xml = "<deliveryreceipt>
<created>2000-12-19T06:29:56+0000</created>
<deliveryMessageId>2ae13f9d-f5dc-4478-ab17-ba13ddeffad2</deliveryMessageId>
<status>ACCEPTED</status>
<clientReference>myref</clientReference>
<part>1</part>
<parts>1</parts>
</deliveryreceipt>";
*/

$receipt = new Blender\Client\DeliveryReceipt($xml);

echo "Receipt Received:\n";
echo "Message Id:\t" . $receipt->getDeliveryMessageId() . "\n";
echo "Reference:\t" . $receipt->getClientReference() . "\n";
echo "Status: \t" . $receipt->getStatus() . "\n";
