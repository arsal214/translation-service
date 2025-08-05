<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/languages",
     *     tags={"Languages"},
     *     summary="Get all available languages",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of languages",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data Get SuccessFully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="locale", type="string", example="en"),
     *                     @OA\Property(property="name", type="string", example="English"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-05T12:34:56Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-05T12:34:56Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $languages = Language::all();
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($languages, 'Data Get SuccessFully', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/languages",
     *     tags={"Languages"},
     *     summary="Add a new language",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale","name"},
     *             @OA\Property(property="locale", type="string", example="fr"),
     *             @OA\Property(property="name", type="string", example="French")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Language added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data Added SuccessFully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="locale", type="string", example="fr"),
     *                 @OA\Property(property="name", type="string", example="French"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-05T12:34:56Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-05T12:34:56Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|unique:languages,locale',
            'name' => 'required|string'
        ]);

        $language = Language::create($request->only('locale', 'name'));

        return $this->sendResponse($language, 'Data Added SuccessFully', 201);
    }
}
