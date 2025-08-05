<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="Tag Management APIs"
 * )
 */
class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tags",
     *     tags={"Tags"},
     *     summary="Get all tags",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Tags retrieved successfully"
     *     )
     * )
     */
    public function index()
    {
        try {
            $tags = Tag::all();
            return $this->sendResponse($tags, 'Tags retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/tags",
     *     tags={"Tags"},
     *     summary="Create a new tag",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="example_tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:tags,name',
        ]);

        try {
            $tag = Tag::create([
                'name' => $request->name,
            ]);
            return $this->sendResponse($tag, 'Tag created successfully.', 201);
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
    }
}
