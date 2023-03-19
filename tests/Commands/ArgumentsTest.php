<?php

namespace Starscy\Project\UnitTests\Commands;

use Starscy\Project\models\Commands\Arguments;
use Starscy\Project\models\Exceptions\ArgumentException;
use PHPUnit\Framework\TestCase;

/**
    * @covers ArgumentsTest
 */

class ArgumentsTest extends TestCase
{

    public function testItReturnsArgumentsValueByName(): void
    {
            // Подготовка
        $arguments = new Arguments(['some_key' => 23]);

            // Действие
        $value = $arguments->get('some_key');
            
            // Проверка
        $this->assertSame("23", $value);
    }


    public function testItReturnsValuesAsStrings(): void
    {
        $arguments = new Arguments(['some_key' => 123]);
        $value = $arguments->get('some_key');
        $this->assertEquals('123', $value);
    }

    public function testItThrowsAnExceptionWhenArgumentIsAbsent(): void
    {
        $arguments = new Arguments([]);
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage("No such argument: some_key");

        $arguments->get('some_key');
    }

    public function argumentsProvider(): iterable
    {
        return [
            ['some_string', 'some_string'], 
            [' some_string', 'some_string'], 
            [' some_string ', 'some_string'],
            [123, '123'],
            [12.3, '12.3'],
            ];
    }


    public function testItConvertsArgumentsToStrings(
        $inputValue,
        $expectedValue
        ): void 
    {
        $arguments = new Arguments(['some_key' => $inputValue]);
        $value = $arguments->get('some_key');
        $this->assertEquals($expectedValue, $value);
    }
}