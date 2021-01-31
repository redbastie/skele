<?php

namespace Redbastie\Skele\Components;

use Illuminate\Database\Eloquent\Model;

class ListingComponent extends Component
{
    public $perPage = 15;
    protected $listeners = ['$refresh', 'infiniteScroll'];

    public function query()
    {
        return Model::query();
    }

    public function getInfiniteScrollProperty()
    {
        return $this->query()->count() > $this->perPage;
    }

    public function infiniteScroll()
    {
        $this->perPage += 15;
    }
}
