<?php

namespace Starscy\Project\UnitTests\Container;

use Starscy\Project\models\Container\DIContainer;
use Starscy\Project\models\Exceptions\NotFoundException;
use Starscy\Project\models\Repositories\User\UserRepositoryInterface;
use Starscy\Project\models\Repositories\User\UserRepository;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{
    public function testItThrowsAnExceptionIfCannotResolveType(): void
    {
        $container = new DIContainer();
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            'Cannot resolve type: Starscy\Project\UnitTests\Container\SomeClass'
        );
        $container->get(SomeClass::class);
    }

    public function testItResolvesClassWithoutDependencies(): void
    {
        $container = new DIContainer();

        $object = $container->get(SomeClassWithoutDependencies::class);
        $this->assertInstanceOf(
            SomeClassWithoutDependencies::class,
            $object
        );
    }

    public function testItResolvesClassByContract(): void
    {

        $container = new DIContainer();
        $container->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $object = $container->get(UserRepositoryInterface::class);

        $this->assertInstanceOf(
        UserRepository::class,
        $object
        );
    }

    public function testItReturnsPredefinedObject(): void
    {
        $container = new DIContainer();

        $container->bind(
        SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );

        $object = $container->get(SomeClassWithParameter::class);

        $this->assertInstanceOf(
            SomeClassWithParameter::class,
        $object
        );

        $this->assertSame(42, $object->value());
    }

    public function testItResolvesClassWithDependencies(): void
    {
        $container = new DIContainer();

        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );
        $object = $container->get(ClassDependingOnAnother::class);

        $this->assertInstanceOf(
            ClassDependingOnAnother::class,
            $object
        );
    }
}