# Database Seeder

[![PHP](https://img.shields.io/packagist/php-v/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spiral-packages/database-seeder/run-tests?label=tests&style=flat-square)](https://github.com/spiral-packages/database-seeder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)

The package provides the ability to seed your database with data using seed classes.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+
- Spiral framework ^3.0
 
## Installation

You can install the package via composer:

```bash
composer require spiral-packages/database-seeder --dev
```

After package install you need to register bootloader from the package.

```php
protected const LOAD = [
    // ...
    \Spiral\DatabaseSeeder\Bootloader\DatabaseSeederBootloader::class,
];
```
> **Note**
> if you are using [`spiral-packages/discoverer`](https://github.com/spiral-packages/discoverer), 
> you don't need to register bootloader by yourself.

## Usage

### Creating entities by Factory

Provides the ability to easily create entities, for example, in tests in your application or packages.

You need to create a factory class, extend it from `Spiral\DatabaseSeeder\Factory\AbstractFactory` and implement 
two methods `entity` and `definition`. Method `entity` must return a fully qualified class name. 
Method `definition` must return an array with generation rules. Keys - properties in the target class. 
Values - property value or calling method that can generate property value (for example, method from Faker).

```php
<?php

declare(strict_types=1);

namespace Database\Seeder;

use App\Entity\User;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class UserFactory extends AbstractFactory
{
    public function entity(): string
    {
        return User::class;
    }

    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'birthday' => \DateTimeImmutable::createFromMutable($this->faker->dateTime()),
            'comments' => CommentFactory::new()->times(3)->make(), // Can use other factories.
            // Be careful, circular dependencies are not allowed!
        ];
    }
}
```
A factory can be created using the method `new`.
```php
$factory = UserFactory::new();
```
A factory has several useful methods:
- `create` - Creates an array of entities, stores them in the database, and returns them for further use in code.
- `createOne` - Creates one entity, stores it in the database, and returns it for further use in code.
- `make` - Creates an array of entities and returns them for further use in code.
- `makeOne` - Creates one entity and returns it for further use in code.
- `raw` - or `data` property. Used to get an array of entity data (raw data, without entity creation).

A few examples:
```php
// 10 users stored in the database
$users = UserFactory::new()->times(10)->create();

// one user stored in the database
$user = UserFactory::new()->createOne();

// 10 users. Entities will not be saved to the database. Only returned for future use
$users = UserFactory::new()->times(10)->make();

// one user. Will not be saved to the database
$user = UserFactory::new()->makeOne();

// array with raw user data
$data = UserFactory::new()->raw();
// or
$data = UserFactory::new()->data;
```

### Seeding
The package provides the ability to seed the database with test data. To do this, create a Seeder class and extend it 
from the `Spiral\DatabaseSeeder\Seeder\AbstractSeeder` class. Implement the `run` method. 
This method should return a generator with entities to store in the database.

```php
<?php

declare(strict_types=1);

namespace Database\Seeder;

use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;

class UserTableSeeder extends AbstractSeeder
{
    public function run(): \Generator
    {
        foreach (UserFactory::new()->times(100)->make() as $user) {
            yield $user;
        }
    }
}
```
## Testing applications with database
The package provides several additional features for easier testing of applications with databases.

> **Note**
Important! Be sure to set up a test database in the test application. Never use a production database for testing!

To use these features, your application's tests must be written using the `spiral/testing` package.

First of all, inherit the base test class that is used in tests using the new functionality.
This will make it possible to use traits to simplify working with the database in tests and provide additional methods for testing.
Example:
```php
<?php

declare(strict_types=1);

namespace Tests\Feature;

abstract class TestCase extends \Spiral\DatabaseSeeder\TestCase
{
}
```

Next, you can add some traits:

### RefreshDatabase
This trait creates the database structure on a first run and wraps the test execution into a transaction. 
After the test runs, the transaction is rollback, but the database structure is saved for use in the next test.

```php
<?php

declare(strict_types=1);

namespace Tests\Feature;

use Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase;

abstract class TestCase extends \Spiral\DatabaseSeeder\TestCase
{
    use RefreshDatabase;
}
```

### DatabaseMigrations
This trait creates a database structure, performs a test, and completely rollback the state of the database.

```php
<?php

declare(strict_types=1);

namespace Tests\Feature;

use Spiral\DatabaseSeeder\Database\Traits\DatabaseMigrations;

abstract class TestCase extends \Spiral\DatabaseSeeder\TestCase
{
    use DatabaseMigrations;
}
```

### DatabaseAsserts
This trait is enabled by default in `Spiral\DatabaseSeeder\TestCase`. Provides additional assertions for 
checking data in a database. Available methods:

- `assertTableExists` - Checks if a table exists in a database
- `assertTableIsNotExists` - Checks if the table is not in the database
- `assertTableCount` - Checks if a table has a certain number of records
- `assertTableHas` - Checks if there is a record in a table that matches a certain condition
- `assertEntitiesCount` - same as `assertTableCount`, but checks by entity, not by table name
- `assertTableHasEntity` - same as `assertTableHas`, but checks by entity, not by table name

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Maxim Smakouz](https://github.com/spiral-packages)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
