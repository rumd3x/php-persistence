# php-persistence
Store persistent data and retrieve it without a database.

## Installation
To install via composer just run
```sh
  composer require rumd3x/php-persistence
```

## Usage
### Getting started
Create an instance of the Persistence Engine passing a string with the driver name.
Every driver is a totally different database.
If data is getting too hard to manage you should probably be using more drivers.
```php
use Rumd3x\Persistence\Engine;
$driver = 'test-db';
$db = new Engine($driver);
```

### Storing data
You can store an instance of any object, arrays of mixed data, strings, etc.
When you retrieve your data, you'll get back exactly what you've stored.
When your data is stored, it's being put at the bottom of a stack.
```php
$array = ['hello' => 'world', 1234, 1.666];
$db->store($array);
```

### Retrieving data
The engine uses FIFO.
So when you retrieve your data, you'll be retrieving from the top of the stack.
If the driver is empty it will return _null_
Also, when you retrieve your data, it will be erased from the stack, so if you are using it again, make sure to store it back.
```php
$data = $db->retrieve($array);
```