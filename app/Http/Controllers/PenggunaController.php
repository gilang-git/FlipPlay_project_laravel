<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\AddToCollectionRequest;
use App\Http\Resources\PenggunaResource;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = Pengguna::with('media')->paginate(10);

        return response()->json([
            'data' => PenggunaResource::collection($pengguna->items()),
            'meta' => [
                'current_page' => $pengguna->currentPage(),
                'last_page' => $pengguna->lastPage(),
                'per_page' => $pengguna->perPage(),
                'total' => $pengguna->total(),
            ],
        ]);
    }

    // Route Model Binding: Laravel otomatis mencari Pengguna berdasarkan ID di URL
    public function show(Pengguna $pengguna)
    {
        return new PenggunaResource($pengguna->load('media'));
    }

    public function store(StorePenggunaRequest $request)
    {
        $pengguna = Pengguna::create($request->validated());
        return (new PenggunaResource($pengguna))->response()->setStatusCode(201);
    }

    public function update(StorePenggunaRequest $request, Pengguna $pengguna)
    {
        $pengguna->update($request->validated());
        return new PenggunaResource($pengguna);
    }

    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();
        return response()->json(['message' => 'Pengguna berhasil dihapus']);
    }

    /**
     * Custom action: menambahkan media ke koleksi pribadi pengguna.
     * POST /api/pengguna/{pengguna}/koleksi
     */
    public function addToCollection(AddToCollectionRequest $request, Pengguna $pengguna)
    {
        $mediaId = $request->validated('media_id');

        // Media yang sama tidak boleh dua kali ada di koleksi pengguna yang sama.
        if ($pengguna->media()->where('media_id', $mediaId)->exists()) {
            return response()->json([
                'message' => 'Media sudah ada di koleksi pengguna ini',
            ], 409);
        }

        $pengguna->media()->attach($mediaId);

        return new PenggunaResource($pengguna->load('media'));
    }

    /**
     * Custom action: menghapus media dari koleksi pribadi pengguna.
     * DELETE /api/pengguna/{pengguna}/koleksi/{media}
     */
    public function removeFromCollection(Pengguna $pengguna, int $media)
    {
        if (!$pengguna->media()->where('media_id', $media)->exists()) {
            return response()->json([
                'message' => 'Media tidak ditemukan di koleksi pengguna ini',
            ], 404);
        }

        $pengguna->media()->detach($media);

        return new PenggunaResource($pengguna->load('media'));
    }
}
