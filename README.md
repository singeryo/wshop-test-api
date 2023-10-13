# WShop test API

This is a simple REST API that allows you to interact with a Store database.

### Stack

- PHP >= 7.4
- MySQL >= 5.7
- composer 2 (only required if you wish to run tests)

### Installation

 - Run database.sql in your database to install DB (currently called ``wshop_api`` in file, feel free to change that)
 - Configure DB values in ``database.json``
 - Requires a vhost to point at root directory. Will not work if you try to run project through a subdirectory like ``localhost:8000/wshop_api/stores``. Something like ``wshop-api.test/stores`` will work.
 - Visit '/' and see the different available routes.
 - You can import ``Insomnia_export.json`` to Insomnia or Postman to get a few requests to test right away (just configure "base_url" variable or replace it in query sting)


### Tests

Some tests have been written, do the following to run them :

- run ``composer install``
- run ``./vendor/bin/phpunit tests --testdox``

How this API was made
-----------------------

This API was made using [Paul Doelles PHP REST API Template](https://github.com/paaull/PHP-REST-API-Template/).
It is quite outdated in terms of code structure and PHP version, so a few adjustments were make, explained below.
However, it has the advantage to be quite simple to understand and to install. 

### Adjustments to original template


- Remove User and Authentication related classes
- Add StoreController and StoreModel
- Add utf8 encoding correction for JsonResponses (accent support)
- Update various code styling using SonarLint
- Inverse a good deal of "if" conditions for more readable functions
- Add customized Exceptions
- Update some array usages with [] for PHP 7
- Add sort and filter functionality
- Add some tests

### More improvement suggestions

- Use a strategy pattern and/or dependency injection for types of Request: have a JsonRequest and XmlRequest that would implement some Request interface to split those features in different files, rather than having a rather cumbersome switch statement in Request.php 
- Url parsing and state features are taking some space in Request.php, we could make it its own class.
- Have a more complete test suite with data fixtures, a specific database for testing

Sample requests
------------------------------

Get stores
------------------------------
GET http://wshop_test.test/users

Get store
------------------------------
GET http://wshop_test.test/stores/1?format=xml

Insert store
------------------------------
POST http://wshop_test.test/stores

{"store":{"name":"Adrian","address":"Smith"}}

Available params : name, address

Sorting
----------

Add which attribute to sort on to query string like so :

GET http://wshop_test.test/stores?sortBy=name


Filtering
----------

Add an array "filter" to specify attributes and values to filter on like so :

GET http://wshop_test.test/stores?filter%5Bname%5D=monde
