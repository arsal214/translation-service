<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/translations",
     *     tags={"Translations"},
     *     summary="List all translations",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function index()
    {
        $perPage = request()->get('paginate', 50);
        $translations = Translation::with('language', 'tags')->paginate($perPage);
        return $this->sendResponse($translations, 'Data Update SuccessFully', 200);
    }
    /**
     * @OA\Post(
     *     path="/api/translations",
     *     tags={"Translations"},
     *     summary="Create a new translation",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"key","value","locale_id"},
     *             @OA\Property(property="key", type="string", example="welcome"),
     *             @OA\Property(property="value", type="string", example="Welcome"),
     *             @OA\Property(property="language_id", type="integer", example=1),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
            'language_id' => 'required|exists:languages,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ]);

        $translation = Translation::create($data);

        if (!empty($data['tags'])) {
            $translation->tags()->sync($data['tags']);
        }

        $translation = $translation->load('language', 'tags');
        return $this->sendResponse($translation, 'Data Added SuccessFully', 201);
    }
    /**
     * @OA\Put(
     *     path="/api/translations/{id}",
     *     tags={"Translations"},
     *     summary="Update an existing translation",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Translation ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="key", type="string", example="updated_key"),
     *             @OA\Property(property="value", type="string", example="Updated Value"),
     *             @OA\Property(property="language_id", type="integer", example=1),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Translation updated")
     * )
     */


    public function update(Request $request, Translation $translation)
    {
        $data = $request->validate([
            'key' => 'string',
            'value' => 'string',
            'language_id' => 'exists:languages,id',
            'tags' => 'array'
        ]);

        $translation->update($data);

        if (isset($data['tags'])) {
            $translation->tags()->sync($data['tags']);
        }

        $translation = $translation->load('language', 'tags');
        return $this->sendResponse($translation, 'Data Update SuccessFully', 201);
    }
    /**
     * @OA\Get(
     *     path="/api/translations/{id}",
     *     tags={"Translations"},
     *     summary="Show a single translation",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Translation ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Translation data")
     * )
     */

    public function show(Translation $translation)
    {
        $translation = $translation->load('language', 'tags');
        return $this->sendResponse($translation, 'Data Update SuccessFully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/translations/search",
     *     tags={"Translations"},
     *     summary="Search translations by key, value, or tag",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="value",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Filtered translation results")
     * )
     */


    public function search(Request $request)
    {
        $query = Translation::with('language', 'tags');

        if ($request->filled('key')) {
            $query->where('key', 'like', "%{$request->key}%");
        }

        if ($request->filled('value')) {
            $query->where('value', 'like', "%{$request->value}%");
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('name', $request->tag));
        }

        return $query->paginate(50);
    }
    /**
     * @OA\Get(
     *     path="/api/translations/export/json",
     *     tags={"Translations"},
     *     summary="Export translations in JSON format",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         required=false,
     *         description="Locale code (e.g., en, fr)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="JSON export of translations")
     * )
     */


    public function export(Request $request)
    {
        $localeCode = $request->get('locale', 'en');
        $locale = Language::where('locale', $localeCode)->firstOrFail();

        $translations = Translation::where('language_id', $locale->id)->get(['key', 'value']);

        return $this->sendResponse($translations->pluck('value', 'key'), 'Data Added SuccessFully', 200);
    }
}
