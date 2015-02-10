# blender-api-php
Blender Bulk SMS Platform - Send SMS via PHP



### Send SMS to single recipient (see Demo.php)
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Send to a single recipient
$response = $bulksms->singleRecipient("originator", "61400000000", "Test SMS", $ROUTE_ID, "my-reference");
```


### Get Route ID (see Routing.php)
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Get route id
$ROUTE_ID = $bulksms->getRouteIdByCountry("Australia");
```


### Send same message to multiple recipients (see Demo.php)
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Create batch
$batch = new Blender\Client\BatchMessageSingleBody();
# Set originator
$batch->setOriginator("test");
# Set route id
$batch->setRouteId($ROUTE_ID);
# Set message body (for all recipients)
$batch->setBody("Batch unicode message with 漢語");

## Recipients ##
$batch->addMSISDN("61000000001");
$batch->addMSISDN("61000000002");
$batch->addMSISDN("61000000003");

# Send message
$responseXml = $bulksms->sendBatch($batch);
```

### Send multiple messages to multiple recipients (see Demo.php)
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Create batch
$batch = new Blender\Client\BatchMessageMultiBody();
# Set default originator (you can override per recipient)
$batch->setOriginator("test");
# Set default route id (you can override per recipient)
$batch->setRouteId($ROUTE_ID);
# Set default message body (you can override per recipient)
$batch->setBody("Batch unicode message with 漢語");

## Recipients ##
# Add recipient
$batch->addRecipient1($recipient);
# Add recipient, override message
$batch->addRecipient2($recipient, "different message");
# Add recipient with unicode message
$batch->addRecipient2($recipient, "different message with unicode טוקיו 東京(Tokyo)");
# Add new message with different originator, recipient and long body
$longtext = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse auctor turpis at nunc rutrum, eget sodales turpis molestie. Nullam mattis sit amet urna et tristique. Vivamus nec justo et dui sed. 200+ chars";
$batch->addRecipient("orignew", $recipient, $longtext);

# Send message
$responseXml = $bulksms->sendBatch($batch);
```



### Receive SMS (see MOHandler.php)

```php
require_once(dirname(__FILE__) . "/bulksms/DeliveryMessage.php");
$xml = $_POST['xml'];
$incomingMessage = new Blender\Client\DeliveryMessage($xml);
$originator = $incomingMessage->getOriginator();
$body = $incomingMessage->getBody();
```
### Receive Receipt (see DRHandler.php)

```php
require_once(dirname(__FILE__) . "/bulksms/DeliveryReceipt.php");
$xml = $_POST['xml'];
$receipt = new Blender\Client\DeliveryReceipt($xml);
$myref = $receipt->getClientReference();
$status = $receipt->getStatus();
```