# CM Messaging SDK
PHP SDK for easy use of CM messaging services. Built with an easy syntax your SMS, Push and/or Voice messages and send them directly with the CM services.
 
 ## Simple example
```php
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

$message = (new \CM\Messaging\Message())
    ->setFrom('Your name/company name')
    ->setTo(['0031612345678', '0031623456789', '0031634567890'])
    ->setBody('Your message');

try {
    $adapter = new GuzzleAdapter(new GuzzleClient());
    $client  = new \CM\Messaging\Client($adapter, 'your-product-token');
    $result  = $client->send($message);
    
    if($result->isAccepted()){
        // All messages were accepted
    } else {
        // Not all messages were accepted
    }
} catch (\CM\Messaging\Exception\BadRequestException $e) {
    // The request failed because of an invalid value, all messages has not been send
} catch (\Http\Client\Exception\TransferException $e) {
    // Something unexpected happened
}
```
 
 ## Requirements
 - CM account with credits, you can register here [https://register.cmtelecom.com](https://register.cmtelecom.com).
 - Composer installed, or load manually
 - PHP >= 5.4
 
 ## Installation
 Run the following command in the root of your project to add the CM Messaging SDK to your project dependencies:
`composer require cmtelecom/php-messaging-sdk`
And
`composer require guzzlehttp/guzzle`
*When using an alternative HttpClient you don't have to add the Guzzle dependencie but it is recomended to use Guzzle. For advanced usage you can look at [http://docs.php-http.org/en/latest/clients.html](http://docs.php-http.org/en/latest/clients.html) for other http clients.
  
 ## Usage
 Instantiate client with your `product token`, which can be found on the Gateway app when you login to [https://gateway.cmtelecom.com](https://gateway.cmtelecom.com).
 ```php
 $client = new \CM\Messaging\Client('your-product-token');
 ```
 
 ### Building a messages
 To create a message you can use an easy one-liner. The required properties are `from`, `to` and `body`. All others can be set optional.
 ```php
use \CM\Messaging\Settings\AllowedChannel;

 $message = (new \CM\Messaging\Message())
        ->setFrom('Your name/company name')
        ->setTo([['0031612345678', '0031623456789', '0031634567890'])
        ->setBody('Message')
        ->setReference('Your message')
        ->setAllowedChannels([AllowedChannel::SMS, AllowedChannel::PUSH, AllowedChannel::VOICE])
        ->setAppKey('your-app-key')
        ->setMinimumNumberOfMessageParts(1)
        ->setMaximumNumberOfMessageParts(8)
        ->setDcs(8);
 ```
 
### Sending a message
After building a message you can send it as following.
 ```php
 $message = (new \CM\Messaging\Message())
         ->setFrom('Your name/company name')
         ->setTo(['0031612345678'])
         ->setBody('Message body');

 try {
    $adapter = new GuzzleAdapter(new GuzzleClient());
    $client  = new \CM\Messaging\Client($adapter, 'your-product-token');
    $result  = $client->send($message);
} catch (\CM\Messaging\Exception\BadRequestException $e) {
    // The request failed because of an invalid value, all messages has not been send
} catch (\Http\Client\Exception\TransferException $e) {
    // Something unexpected happened
}
 ```
  
### Sending a batch of messages
When you send a batch of messages, this is when you have multiple messages with different body's. You can send all of them in one request by making an array of messages. 
If the body is the same for all recipients you can simply add the array with the recipients phone numbers in the `setTo()`, you won't have to make multiple messages is this case (as shown in the simple example).

 ```php
$message_1 = (new \CM\Messaging\Message())
        ->setFrom('Your name/company name')
        ->setTo(['0031612345678'])
        ->setBody('Message one');
$messages[] = $message_1;

$message_2 = (new \CM\Messaging\Message())
        ->setFrom('Your name/company name')
        ->setTo(['0031623456789', '0031634567890'])
        ->setBody('Message two');
$messages[] = $message_2; 

try {
    $adapter = new GuzzleAdapter(new GuzzleClient());
    $client  = new \CM\Messaging\Client($adapter, 'your-product-token');
    $result  = $client->send($messages);
} catch (\CM\Messaging\Exception\BadRequestException $e) {
    // The request failed because of an invalid value, all messages has not been send
} catch (\Http\Client\Exception\TransferException $e) {
    // Something unexpected happened
}
 ```
 
### Handle response
After sending a message you get a response back that contains the accepted and failed messages. This way you can validate if the messages are processed as expected. You can do this for all messages or optionally add a phone number or an array of phone numbers to check those specific messages. 
 ```php
try {
    $result = $client->send($messages);
    
    // returns true if all messages are accepted
    $result->isAccepted() 
    // returns true if messages with these phone numbers are accepted
    $result->isAccepted(['0031612345678', '0031623456789'])
    
    // returns true if all messages are failed
    $result->isFailed()
    // returns true if messages with these phone numbers are failed
    $result->isFailed(['0031612345678', '0031623456789'])
    
    // returns an array with the accepted message responses
    $result->getAccepted() 
    // returns an array with the accepted message responses containging these phone numbers
    $result->getAccepted(['0031612345678', '0031623456789'])
    
    // returns an array with the failed message responses 
    $result->getFailed()
    // returns an array with the failed message responses containging these phone numbers
    $result->getFailed(['0031612345678', '0031623456789'])
    
} catch (\CM\Messaging\Exception\BadRequestException $e) {
    // The request failed because of an invalid value, all messages has not been send
    
    // Returns body contents of the response with detailed error message(s)
    $contents = $e->getResponse()->getBody()->getContents());
} catch (\Http\Client\Exception\TransferException $e) {
    // Something unexpected happened
}
 ```
For more detailed error handling you can optionally catch `RequestException`, `HttpException` and/or `NetworkException` which are child's of the more generic `TransferException` exception.

It is also possible to retrieve the PSR-7 response, this can be done with `getResponse()`. The response body content can be retrieved with `getResponse()->getBody()->getContents()`.
 
 
### Advanced usage
#### Default properties
If you call multiple times the `$client->send()` method and you want certain properties for all messages send in your application you can set these properties on the `$client`. Properties set in the `Message` will still overwrite the properties set in `$client`.
 ```php
$client = (new \CM\Messaging\Client('your-product-token'))
        ->setReference('Your reference')
        ->setAllowedChannels([AllowedChannel::SMS, AllowedChannel::PUSH, AllowedChannel::VOICE])
        ->setAppKey('your-app-key')
        ->setMinimumNumberOfMessageParts(1)
        ->setMaximumNumberOfMessageParts(8)
        ->setDcs(8);
 ```
 
#### Strategies
 By default this SDK will remove duplicate phone numbers within the same message because it is assumed you don't wan't to send the exact same message twice to the same phone number at the same time. If you wan't to disable this strategy/behaviour you can add the following to you `send()` method.
  ```php
$client->send($messages, ['strategy' => ['keep_duplicate_phone_numbers']]);
  ```
  
#### Configuration Exceptions
Exception thrown | Description
 --- | ---
 InvalidConfigurationException | The configuration is not valid
 └ InvalidAllowedChannelException | One or more AllowedChannel(s) is not a valid option
 └ InvalidStrategyException | One or more stratagie(s) is not a valid option
 
#### Http Exceptions
 Status code | Exception thrown | Description
 --- | --- | ---
 **null** | TransferException | Something unexpected happened
 └  **null** | RequestException | The request is invalid
 &nbsp;&nbsp;&nbsp; └ **400-499** | HttpException | Client-side error
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; └ **400** | BadRequestException | The request failed because of an invalid value, all messages has not been send
 &nbsp;&nbsp;&nbsp; └ **500-599** | NetworkException | Server-side error

## Methods
### Client
 Method | Parameters | Return 
 --- | --- | --- 
**__construct()** | $productToken, HttpClient $httpClient | void 
**send()** | array/Message $messages, array/null $parameters | Response 
**getBodyType()** |  | BodyType 
**setBodyType()** | BodyType $bodyType | $this 
**getDcs()** |  | int 
**setDcs()** | int $dcs | $this 
**getReference()** |  | string 
**setReference()** | string $reference | $this 
**getMinimumNumberOfMessageParts()** |  | int 
**setMinimumNumberOfMessageParts()** | int $minimumNumberOfMessageParts | $this 
**getMaximumNumberOfMessageParts()** |  | int 
**setMaximumNumberOfMessageParts()** | int $minimumNumberOfMessageParts | $this 
**getAppKey()** |  | string 
**setAppKey()** | string $appKey | $this 
**getAllowedChannels()** |  | array 
**setAllowedChannels()** | array/AllowedChannel $allowedChannels | $this 
**getProductToken()** |  | string 

### Message
 Method | Parameters | Return
 --- | --- | ---
**getFrom()** |  | array 
**setFrom()** | string $from | $this 
**getTo()** |  | array 
**setTo()** | array/string $to | $this 
**getBody()** |  | string 
**setBody()** | string $body, BodyType/null $bodyType | $this 
**getDcs()** |  | int 
**setDcs()** | int $dcs | $this 
**getReference()** |  | string 
**setReference()** | string $reference | $this 
**getMinimumNumberOfMessageParts()** |  | int 
**setMinimumNumberOfMessageParts()** | int $minimumNumberOfMessageParts | $this 
**getMaximumNumberOfMessageParts()** |  | int 
**setMaximumNumberOfMessageParts()** | int $minimumNumberOfMessageParts | $this 
**getAppKey()** |  | string 
**setAppKey()** | string $appKey | $this 
**getAllowedChannels()** |  | array 
**setAllowedChannels()** | array/AllowedChannel $allowedChannels | $this 
**getProductToken()** |  | string 

### Response
 Method | Parameters | Return 
 --- | --- | --- 
**getDetails()** |  | string
**getAccepted()** | array/string/null $phoneNumbers | array /null
**isAccepted()** | array/string/null $phoneNumbers | bool 
**getFailed()** | array/string/null $phoneNumbers | array/null 
**isFailed()** | array/string/null $phoneNumbers | bool 
**getResponse()** |  | PSR-7 Response 

## Todo
- Custom grouping support