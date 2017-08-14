AddressBook
===========

Write a Symfony 3 application for an address book.
Person in address book should have following fields: name, email, phone, address, latitude, longitude.
Name and email should be unique.
Address book should do the following:
* view a list on people (name, email)
* view person details (all data)
* add person to address book
* edit person in address book
* delete person from address book

When a person is added or address changed the application should use Google Maps Geocoding API (https://developers.google.com/maps/documentation/geocoding/start) to fetch latitude and longitude for the address

Optional:
* paginate list when number of people gets high
* add a link to list or person detail view that will open Google Maps with the appropriate coordinates
* test your code


### Important

Project was done on Ubuntu 16.04

I used the default bundle. Added only one new class AddressBookController.php
You can see pagination when you have more that 5 items in book.

To launch project I used in build server using

~~~
php bin/console server:start
~~~

You need first to:
~~~
php bin/console doctrine:schema:update --force
~~~

My url was:

~~~
http://localhost:8000/addresses
~~~

The configurations I used:

~~~
parameters:
    database_host: 127.0.0.1
    database_port: null
    database_name: AddressDatabase
    database_user: root
    database_password: null
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    secret: ThisTokenIsNotSoSecretChangeIt
~~~
