<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LokasiArsipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $lokasiId = $this->route('lokasi_arsip')?->id ?? $this->route('lokasi_arsip');

        return [
            'kode_lokasi' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('lokasi_arsip', 'kode_lokasi')
                    ->ignore($lokasiId)
                    ->whereNull('deleted_at'),
            ],
            'gedung' => ['required', 'string', 'max:100'],
            'ruang' => ['required', 'string', 'max:100'],
            'rak' => ['required', 'string', 'max:50'],
            'boks' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'kapasitas' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'kode_lokasi' => 'kode lokasi',
            'gedung' => 'gedung',
            'ruang' => 'ruang',
            'rak' => 'rak',
            'boks' => 'boks',
            'keterangan' => 'keterangan',
            'kapasitas' => 'kapasitas',
            'is_active' => 'status aktif',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode_lokasi.unique' => 'Kode lokasi sudah digunakan.',
            'kode_lokasi.max' => 'Kode lokasi maksimal 30 karakter.',
            'gedung.required' => 'Nama gedung wajib diisi.',
            'gedung.max' => 'Nama gedung maksimal 100 karakter.',
            'ruang.required' => 'Nama ruang wajib diisi.',
            'ruang.max' => 'Nama ruang maksimal 100 karakter.',
            'rak.required' => 'Nomor/nama rak wajib diisi.',
            'rak.max' => 'Nomor/nama rak maksimal 50 karakter.',
            'boks.required' => 'Nomor/nama boks wajib diisi.',
            'boks.max' => 'Nomor/nama boks maksimal 50 karakter.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal 0.',
            'kapasitas.max' => 'Kapasitas maksimal 9999.',
        ];
    }
}
