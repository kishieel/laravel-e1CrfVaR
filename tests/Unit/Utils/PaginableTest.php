<?php

declare(strict_types=1);

namespace Tests\Unit\Utils;

use App\Utils\Paginable;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class PaginableTest extends TestCase
{
    use Paginable;

    /**
     * @test
     * @covers \App\Utils\Paginable::getPaging
     */
    public function it_should_return_default_values(): void
    {
        $request = new Request();

        $result = $this->getPaging($request);
        $this->assertSame([
            'page' => 1,
            'limit' => 10,
            'sort' => [],
            'search' => null,
        ], $result);
    }

    /**
     * @test
     * @covers \App\Utils\Paginable::getPaging
     */
    public function it_should_return_custom_values(): void
    {
        $request = new Request();
        $request->merge([
            'page' => 2,
            'limit' => 20,
            'sort' => ['name:desc', 'created_at:asc'],
            'search' => 'keyword',
        ]);

        $result = $this->getPaging($request, ['name', 'created_at']);

        $this->assertSame([
            'page' => 2,
            'limit' => 20,
            'sort' => [
                ['column' => 'name', 'direction' => 'desc'],
                ['column' => 'created_at', 'direction' => 'asc'],
            ],
            'search' => 'keyword',
        ], $result);
    }

    /**
     * @test
     * @covers \App\Utils\Paginable::getPaging
     */
    public function it_should_sanitize_custom_values(): void
    {
        $request = new Request();
        $request->merge([
            'page' => 2,
            'limit' => 200,
            'sort' => ['name:xxx', 'created_at:asc'],
            'search' => 'keyword',
        ]);

        $result = $this->getPaging($request, ['name']);

        $this->assertSame([
            'page' => 2,
            'limit' => 100,
            'sort' => [['column' => 'name', 'direction' => 'asc']],
            'search' => 'keyword',
        ], $result);
    }
}
