# Export Twilio Message Logs to CSV

## Requirements

* Twilio account
* Webserver - Heroku was used for this example
* PHP
* Composer

## Instructions

1. Load this code to a Heroku server.
2. Add your Twilio Account SID and Auth Token as environment variables.
3. Change the filters passed in to meet your needs. You can use `to`, `from`, `dateSent`, `dateSentAfter`, and `dateSentBefore`.

```php
$messages = $client->messages->stream(array
    (   
      'dateSentAfter' => '2017-02-23', 
      'dateSentBefore' => '2017-02-27',
    )
);
```

4. Visit the Heroku url in a browser and you will be prompted to download a CSV file.