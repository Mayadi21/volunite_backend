<?php

namespace App\Http\Requests\Organizer;

use Illuminate\Foundation\Http\FormRequest;

class StoreKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'judul'            => 'required|string|max:100',
            'deskripsi'        => 'required|string|max:150',
            'lokasi'           => 'required|string|max:150',
            'syarat_ketentuan' => 'required|string',
            'kuota'            => 'required|integer|min:1',
            'tanggal_mulai'    => 'required|date|after:now',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'status'           => 'nullable|in:Waiting,scheduled',
            'thumbnail'        => 'required|image|mimes:jpeg,png,jpg,webp|max:20480', 
            'kategori_ids'     => 'required|array|min:1',
            'kategori_ids.*'   => 'exists:kategori,id',
        ];
    }
}