<?php

namespace App\Http\Requests\Organizer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'            => 'sometimes|string|max:100',
            'deskripsi'        => 'sometimes|string|max:150',
            'link_grup'        => 'sometimes|url|max:255',
            'lokasi'           => 'sometimes|string|max:150',
            'syarat_ketentuan' => 'sometimes|string',
            'kuota'            => 'sometimes|integer|min:1',
            'tanggal_mulai'    => 'sometimes|date',
            'tanggal_berakhir' => 'sometimes|date|after:tanggal_mulai',
            'status'           => 'nullable|in:Waiting,scheduled,finished,cancelled',
            'thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'kategori_ids'     => 'sometimes|array',
            'kategori_ids.*'   => 'exists:kategori,id',
        ];
    }
}