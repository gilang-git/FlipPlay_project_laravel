<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Menampilkan seluruh media, dengan opsi pencarian berdasarkan judul.
     * Contoh: GET /api/media?search=batman
     */
    public function index(Request $request)
    {
        $media = Media::with('pengguna')
            ->when($request->query('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->paginate(10);

        return MediaResource::collection($media);
    }

    // Route Model Binding: Laravel otomatis mencari Media berdasarkan ID di URL
    public function show(Media $medium)
    {
        return new MediaResource($medium->load('pengguna'));
    }

    public function store(StoreMediaRequest $request)
    {
        $media = Media::create($request->validated());
        return (new MediaResource($media))->response()->setStatusCode(201);
    }

    public function update(StoreMediaRequest $request, Media $medium)
    {
        $medium->update($request->validated());
        return new MediaResource($medium);
    }

    public function destroy(Media $medium)
    {
        $medium->delete();
        return response()->json(['message' => 'Media berhasil dihapus']);
    }
}
