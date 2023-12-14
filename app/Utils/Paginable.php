<?php

declare(strict_types=1);

namespace App\Utils;

use function explode;

use Illuminate\Http\Request;

use function in_array;
use function is_array;
use function min;

trait Paginable
{
    private int $pageSizeLimit = 100;
    private string $sortSeparator = ':';
    private array $directionWhitelist = ['asc', 'desc'];
    private string $defaultDirection = 'asc';

    public function getPaging(Request $request, array $sortWhitelist = []): array
    {
        $page = $request->input('page', 1);
        $limit = min($request->input('limit', 10), $this->pageSizeLimit);
        $search = $request->input('search');

        $sort = [];
        $sortOfRequest = is_array($request->input('sort', [])) ? $request->input('sort', []) : [$request->input('sort')];
        foreach ($sortOfRequest as $item) {
            [$column, $direction] = explode($this->sortSeparator, $item) + [null, $this->defaultDirection];
            if (! in_array($column, $sortWhitelist, true)) {
                continue;
            }
            if (! in_array($direction, $this->directionWhitelist, true)) {
                $direction = $this->defaultDirection;
            }
            $sort[] = ['column' => $column, 'direction' => $direction];
        }

        return [
            'page' => $page,
            'limit' => $limit,
            'sort' => $sort,
            'search' => $search,
        ];
    }
}
