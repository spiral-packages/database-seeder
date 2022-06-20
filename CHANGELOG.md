# Changelog

## 2.0.0 - Unreleased
- **High Impact Changes**
  - Dropped support for Spiral Framework 2.x
  - Method `create` in `Spiral\DatabaseSeeder\Factory\FactoryInterface` interface and 
    `Spiral\DatabaseSeeder\Factory\AbstractFactory` class renamed to `make`
  - Method `createOne` in `Spiral\DatabaseSeeder\Factory\FactoryInterface` interface and 
  `Spiral\DatabaseSeeder\Factory\AbstractFactory` class renamed to `makeOne`

- **Medium Impact Changes**
  - Min PHP version increased to 8.1

- **Other Features**
  - Added `Spiral\DatabaseSeeder\TestCase` class, which can use as a base class in tests that work with the database. 
    It adds the ability to use traits in test classes that add functionality for testing database applications. 
    To use this class, you must use the `spiral/testing` package
  - Added `Spiral\DatabaseSeeder\Database\Traits\RefreshDatabase` trait. 
    This trait automatically creates the database structure the first time any test is run, and refresh the database before each test is run.
  - Added `Spiral\DatabaseSeeder\Database\Traits\DatabaseMigrations` trait.
    This trait creates a database structure using migrations before each test execution. After each test runs, 
    it refreshes the database and rollback migrations.

## 1.0.0 - 202X-XX-XX

- initial release
