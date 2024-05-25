<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Interfaces\TagServiceInterface;
use App\Models\Tag;
use App\Http\Requests\CreateTagRequest;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    private $tagService;

    public function __construct(TagServiceInterface $tagService)
    {
        $this->tagService = $tagService;
    }

    public function create(CreateTagRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->tagService->create($request);
        });
    }

    public function delete(Tag $tag)
    {
        return $this->handleServiceCall(function () use ($tag) {
            return $this->tagService->delete($tag);
        });
    }
}