The implementation of test task for CurrencyFair.

Implemented 2 layers from 3 required.
1. JSON data input layer
2. Backend storing and aggregation layer

Task implemented on PHP using Symfony 2.6.* framework.
Dependencies are managed by composer:

Architecture notes.
1. JSON data input layer
JSON data input layer provided in conroller /data_input.
The missions of the controller:
- accept POST HTTP method only.
- check JSON schema
- filter posted JSON data
- send accepted data to message queue (AMQP message queue)

2. Backend storing and aggregation layer

The backend is split in two general parts:
- second stage filtering and message queues multiplexing part
- aggregation and data storage part

Both parts implemented as console workers that can be scaled horisontally.
All workers provided in console comands.
All commands have common set of options:
- chunk size - count of messages received in one iteration (default value - 1)
- sleep period - idle period between iterations in seconds (default value - 1)

Second stage filtering worker provided in console comand 

./console api:processInput [-chsz|--chunkSize[="..."]] [-sp|--sleepPeriod[="..."]]

The tasks of the worker:
- receiving messages from frontend queue
- filtering messages by allowed currencies and countries
- sending filtered messages to aggregation and store queues

Console comand for worker, storing transactions history:

./console internalData:messagesSaver [-chsz|--chunkSize[="..."]] [-sp|--sleepPeriod[="..."]]

The task of the worker - storing all filtered messages in DB.

Another implemented worker - transaction counters by currency pairs and countries
The worker provided in console command 

./console internalData:countersByPair [-chsz|--chunkSize[="..."]] [-sp|--sleepPeriod[="..."]] currencyFrom currencyTo

The comand has additional two parameters currencyFrom currencyTo (for example ./console internalData:countersByPair EUR GBP )
The worker aggregates and stores total sold amount, total bought amount, and transactions count by country.

All data is saved to mysql db usinf Doctrine ORM.

NOTE:
Tests not implemented at the moment. Implementation in progress.

