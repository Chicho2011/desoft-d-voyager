<?php

namespace Desoft\DVoyager\Services;

use Desoft\DVoyager\Models\DVoyagerSearch;

class SearchServices {

    public function insertSearchableItem(mixed $object, string $content)
    {
        $newSearch = $object->search()->create(['content' => $content]);

        return $newSearch;
    }

    public function deleteSearchableItem($object)
    {
        $object->search()->delete();
    }

    public function search(string $textToSearch, int $page = 1, int $perPage = 10)
    {
        $result = DVoyagerSearch::
                                where('content', 'LIKE', '%'.$textToSearch.'%')
                                ->paginate($perPage, ['*'], 'page', $page)
                                ;

        return $result;
    }

}