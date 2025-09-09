<?php

namespace Wiz\FilamentExtend\Traits;

trait HasPaging
{

    public int $page = 1;

    public int $itemPerPage = 36;

    public function gotoPage($page): void
    {
        $this->page = $page;
    }

    public function previousPage(): void
    {
        $this->page--;
    }

    public function nextPage(): void
    {
        $this->page++;
    }
}
