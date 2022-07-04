PathCase Symfony Restful Application
====================================

The "PathCase Symfony Restful Application" is case application.


Requirements
------------

* PHP 8.1.0 or higher;
* and the [usual Symfony application requirements][1].

Installation
------------

[Download Symfony][2] to install the `symfony` binary on your computer.

```bash
 git clone https://github.com/aerdem/path.git   
 cd path
 composer install
 php bin/console doctrine:database:create
 php bin/console make:migration
 php bin/console doctrine:schema:update --force
 php bin/console lexik:jwt:generate-keypair
 symfony server:start
```

Visit
-----

view symfony server:start terminal output and visit server listenin address

```bash
[OK] Web server listening                                                                                              
The Web server is using PHP FPM 8.1.7                                                                             
http://127.0.0.1:8000
```

Api Test 
--------

Methods
* /register - Add a new user
* /auth/token - Get access token with username and password
* /order/add - Add a new order
* /order/update/{id} - Update a order with order id
* /orders - Get all order records
* /orders/{id} - Get a record detail with order id

[View And Import Postman Collection][3]

[1]: https://symfony.com/doc/current/setup.html#technical-requirements
[2]: https://symfony.com/download
[3]: https://www.getpostman.com/collections/e91bb777dae466e5c5ca