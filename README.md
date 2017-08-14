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

I used the dafault bundle. Added only one new class AddressBookController.php

You can see pagination when you have more that 5 items in book.
