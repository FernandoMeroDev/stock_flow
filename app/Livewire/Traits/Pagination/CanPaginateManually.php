<?php

namespace App\Livewire\Traits\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

trait CanPaginateManually
{
    protected function paginate(
        object $collection, 
        int $perPage = 15, 
        string $pageName = 'page', 
        $query_method = 'query'
    )
    {
        $paginator = new LengthAwarePaginator(
            $collection->slice(
                ($this->getPage($pageName) - 1) * $perPage,
                $perPage
            ), 
            $collection->count(), 
            $perPage, 
            $this->getPage($pageName), 
            ['pageName' => $pageName]
        );
        return $this->resetPageIfEmpty($paginator, $query_method);
    }

    protected function resetPageIfEmpty(LengthAwarePaginator $paginator, string $query_method)
    {
        if($paginator->isEmpty() && $paginator->currentPage() !== 1){
            $this->resetPage($paginator->getPageName());
            return $this->{$query_method}();
        }
        return $paginator;
    }
}