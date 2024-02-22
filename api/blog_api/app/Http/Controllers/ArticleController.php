<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Eager loading for better performance
        $articles = Article::paginate(3);

        return response()->json($articles);
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        return response()->json($article);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleRequest $request
     * @return JsonResponse
     */
    public function store(ArticleRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $imageName = $this->uploadImage($request->image);

            $validated['image'] = $imageName;

            $article = Article::create($validated);

            return response()->json($article, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleRequest $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(ArticleRequest $request, Article $article): JsonResponse
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                Storage::delete('images/articles/' . $article->image);
                $imageName = $this->uploadImage($request->image);
                $validated['image'] = $imageName;
            }

            $article->update($validated);

            return response()->json($article);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        try {
            Storage::delete('images/articles/' . $article->image);
            $article->delete();
            return response()->json(['message' => 'Article deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * upload image to storage and return the name of the image
     * @param $image
     * @return string
     */
    private function uploadImage($image): string
    {
        $imageName = Str::uuid() . '.' . $image->extension();

        Storage::disk('public')->put('images/articles/' . $imageName, file_get_contents($image));

        return $imageName;
    }
}
