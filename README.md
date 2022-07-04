PathCase Symfony Restful Application
====================================
The "PathCase Symfony Restful Application" is a case application.

Requirements
------------
* PHP 8.0 or higher;
* and the [usual Symfony application requirements][1].

Installation
------------
[Download Symfony][2] to install the `symfony` binary on your computer.

And run following codes;
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
Output;
```bash
[OK] Web server listening                                                                                              
The Web server is using PHP FPM 8.1.7                                                                             
http://127.0.0.1:8000
```

Create Dummy Data
-----------------
Run for 3 user and 10 order related with users.
```
php bin/console doctrine:fixtures:load
```
Output: 
```
Careful, database "" will be purged. Do you want to continue? (yes/no) [no]:
> yes

> purging database
> loading App\DataFixtures\AppFixtures
Array
(
[username] => cetin.kutay
[password] => B:!,u"&-I_5z5Jo
[firstname] => Utku
[lastname] => Eronat
[hashed] => $2y$13$qa4O.IaivvdkiM.3nGqXA.4.ATNwR3Acg7a4lprvviajHQTMgCCb2
)
Array
(
[username] => ali59
[password] => pdt?"C
[firstname] => Ece
[lastname] => Baykam
[hashed] => $2y$13$BMGc9NFcl9q.HZZB3FFfPuaCnYwlsY2whdn5F7Uj6rzwgD2P/NFsa
)
Array
(
[username] => bkasapoglu
[password] => vH({&b7aGhxUKa)txr
[firstname] => Canberk
[lastname] => Akay
[hashed] => $2y$13$15HrsHn45wMigyJo3IOBkuCV0oF/Ip2n39ijTu1YAQA37yg7uJ5eO
)
> loading App\DataFixtures\OrderFixtures
```
You can use any user data without create new user. 

Api Methods 
--------
Public:
* /register - Add a new user 
* /user_list - View all users
* /auth/token - Get access token with username and password

With JWT Token
* /order/add - Add a new order
* /order/update/{id} - Update a order with order id - Only use with JWTOnly use with JWT
* /orders - Get all order records
* /orders/{id} - Get a record detail with order id

[View And Import Postman Collection][3]

[1]: https://symfony.com/doc/current/setup.html#technical-requirements
[2]: https://symfony.com/download
[3]: https://www.getpostman.com/collections/e91bb777dae466e5c5ca

