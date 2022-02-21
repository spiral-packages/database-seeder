# Database Seeder

[![PHP](https://img.shields.io/packagist/php-v/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spiral-packages/database-seeder/run-tests?label=tests&style=flat-square)](https://github.com/spiral-packages/database-seeder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)

The package provides the ability to seed your database with data using seed classes.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.0+
- Spiral framework 2.9+
 
## Installation

You can install the package via composer:

```bash
composer require spiral-packages/database-seeder
```

After package install you need to register bootloader from the package.

```php
protected const LOAD = [
    // ...
    \Spiral\DatabaseSeeder\Bootloader\DatabaseSeederBootloader::class,
];
```

> Note: if you are using [`spiral-packages/discoverer`](https://github.com/spiral-packages/discoverer), 
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

namespace App\Tests\Fixture\Factory;

use App\Tests\Fixture\Entity\User;
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
            'comments' => CommentFactory::new()->times(3)->create(), // Can use other factories.
            // Be careful, circular dependencies are not allowed!
        ];
    }
}
```

After that, you can use this factory in your code. For example:
```php
<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Tests\Fixture\Factory\UserFactory;

class ExampleTest extends TestCase
{
    public function testExample(): void
    {
        // creating one user
        $user = UserFactory::new()->createOne();
        
        // creating an array of ten users
        $users = UserFactory::new()->times(10)->create();
        
        // creating a user with some specific data
        $user = UserFactory::new(['firstName' => 'John', 'lastName' => 'Doe'])->createOne();
    
        // using callback, after creating entity
        $user = UserFactory::new()->afterCreate(fn(User $user) => $user->firstName = 'Nick')->createOne();
    }
}
```

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
