<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Page\PageCollection;
use App\Http\Resources\Page\PageResource;
use App\Models\Page;

class PagesController extends Controller
{
    /**
     * @return PageCollection
     */
    public function index()
    {
        $pages = Page::all();

        return new PageCollection($pages);
    }

    /**
     * @param $alias
     * @return PageResource
     */
    public function show($alias)
    {
        $page = Page::where('alias', $alias)->firstOrFail();

        return new PageResource($page);
    }
}
