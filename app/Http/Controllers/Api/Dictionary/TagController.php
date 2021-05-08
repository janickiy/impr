<?php

namespace App\Http\Controllers\Api\Dictionary;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

/**
 * @group Справочник (dictionary).
 */
class TagController extends Controller
{
    /**
     * Теги/интересы (tags/interests).
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(Tag::all());
    }
}
