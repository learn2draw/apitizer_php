<?php

namespace Tests\Unit\Types;

use Apitizer\QueryBuilder;
use Apitizer\Types\Field;
use ArrayAccess;
use Tests\Unit\TestCase;
use Tests\Feature\Builders\UserBuilder;
use UnexpectedValueException;

class FieldTest extends TestCase
{
    /** @test */
    public function it_accepts_array_accessible_objects_when_rendering()
    {
        // E.g. Eloquent models, collections, etc
        $collection = collect(['key' => 'value']);
        $rendered = $this->field()->render($collection);

        $this->assertInstanceOf(ArrayAccess::class, $collection);
        $this->assertEquals('value', $rendered);
    }

    /** @test */
    public function non_nullable_fields_throw_when_null_value_is_rendered()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->field()->render(['key' => null]);
    }

    /** @test */
    public function nullable_fields_dont_throw_when_null_value_is_rendered()
    {
        $rendered = $this->field()->nullable()->render(['key' => null]);

        $this->assertEquals(null, $rendered);
    }

    private function field(QueryBuilder $queryBuilder = null, string $key = 'key', string $type = 'string')
    {
        return new Field(
            $queryBuilder ?? new UserBuilder($this->buildRequest()),
            $key,
            $type
        );
    }
}