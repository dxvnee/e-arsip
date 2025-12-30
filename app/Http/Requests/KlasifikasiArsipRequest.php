<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KlasifikasiArsipRequest extends FormRequest
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
        $klasifikasiId = $this->route('klasifikasi_arsip')?->id ?? $this->route('klasifikasi_arsip');

        return [
            'kode_klasifikasi' => [
                'required',
                'string',
                'max:20',
                Rule::unique('klasifikasi_arsip', 'kode_klasifikasi')
                    ->ignore($klasifikasiId)
                    ->whereNull('deleted_at'),
            ],
            'nama_klasifikasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'retensi_aktif' => ['required', 'integer', 'min:0', 'max:100'],
            'retensi_inaktif' => ['required', 'integer', 'min:0', 'max:100'],
            'nasib_akhir' => ['required', Rule::in(['musnah', 'permanen'])],
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
            'kode_klasifikasi' => 'kode klasifikasi',
            'nama_klasifikasi' => 'nama klasifikasi',
            'deskripsi' => 'deskripsi',
            'retensi_aktif' => 'retensi aktif',
            'retensi_inaktif' => 'retensi inaktif',
            'nasib_akhir' => 'nasib akhir',
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
            'kode_klasifikasi.required' => 'Kode klasifikasi wajib diisi.',
            'kode_klasifikasi.unique' => 'Kode klasifikasi sudah digunakan.',
            'kode_klasifikasi.max' => 'Kode klasifikasi maksimal 20 karakter.',
            'nama_klasifikasi.required' => 'Nama klasifikasi wajib diisi.',
            'nama_klasifikasi.max' => 'Nama klasifikasi maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
            'retensi_aktif.required' => 'Retensi aktif wajib diisi.',
            'retensi_aktif.integer' => 'Retensi aktif harus berupa angka.',
            'retensi_aktif.min' => 'Retensi aktif minimal 0 tahun.',
            'retensi_aktif.max' => 'Retensi aktif maksimal 100 tahun.',
            'retensi_inaktif.required' => 'Retensi inaktif wajib diisi.',
            'retensi_inaktif.integer' => 'Retensi inaktif harus berupa angka.',
            'retensi_inaktif.min' => 'Retensi inaktif minimal 0 tahun.',
            'retensi_inaktif.max' => 'Retensi inaktif maksimal 100 tahun.',
            'nasib_akhir.required' => 'Nasib akhir wajib dipilih.',
            'nasib_akhir.in' => 'Nasib akhir harus Musnah atau Permanen.',
        ];
    }
}
