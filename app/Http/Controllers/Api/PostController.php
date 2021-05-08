<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SavePostRequest;
use App\Http\Resources\Post\EditPostResource;
use App\Http\Resources\Post\ShowPostResource;

/**
 * @group Пост.
 */
class PostController extends Controller
{
    /**
     * PostController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    /**
     * Лента постов (post list).
     *
     * @authenticated
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json();
    }

    /**
     * Создание поста (post create).
     *
     * @authenticated
     *
     * @param SavePostRequest $request
     *
     * @return JsonResponse
     */
    public function store(SavePostRequest $request): JsonResponse
    {
        $post = $request->user()
            ->posts()
            ->create($request->except(['tags', 'covers']));

        $post->tags()->sync($request->get('tags'));
        $post->covers()->sync($request->get('covers'));

        return response()->json(new EditPostResource($post), Response::HTTP_CREATED);
    }

    /**
     * Просмотр поста (show post).
     *
     * @authenticated
     *
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        $post->increment('shows');

        return response()->json(new ShowPostResource($post));
    }

    /**
     * Просмотр поста (show post).
     *
     * @authenticated
     *
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function edit(Post $post): JsonResponse
    {
        return response()->json(new EditPostResource($post));
    }

    /**
     * Обновление поста (post update).
     *
     * @authenticated
     *
     * @param SavePostRequest $request
     * @param Post            $post
     *
     * @return JsonResponse
     */
    public function update(SavePostRequest $request, Post $post): JsonResponse
    {
        $post->update($request->except(['tags', 'covers']));
        $post->tags()->sync($request->get('tags'));
        $post->covers()->sync($request->get('covers'));

        return response()->json(new EditPostResource($post));
    }

    /**
     * Удаление поста (delete post).
     *
     * @authenticated
     *
     * @param Post $post
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
