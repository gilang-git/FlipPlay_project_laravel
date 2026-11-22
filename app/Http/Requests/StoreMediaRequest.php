<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isPatch = $this->isMethod('PATCH');

        // Judul tidak boleh duplikat, kecuali dengan media itu sendiri saat update.
        $uniqueTitle = Rule::unique('media', 'title')->ignore($this->route('medium'));

        return [
            'title' => $isPatch
                ? ['sometimes', 'string', 'max:255', $uniqueTitle]
                : ['required', 'string', 'max:255', $uniqueTitle],
            'type' => $isPatch ? 'sometimes|string|in:movie,series' : 'required|string|in:movie,series',
            'genre' => $isPatch ? 'sometimes|string|max:255' : 'required|string|max:255',
            'release_year' => $isPatch ? 'sometimes|integer|min:1900' : 'required|integer|min:1900',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi',
            'title.unique' => 'Judul media sudah terdaftar, tidak boleh duplikat',
            'type.required' => 'Jenis media wajib diisi',
            'type.in' => 'Jenis media harus movie atau series',
            'genre.required' => 'Genre media wajib diisi',
            'release_year.required' => 'Tahun rilis wajib diisi',
        ];
    }
}
