<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BerkasArsipRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $berkasId = $this->route('berkas_arsip')?->id ?? $this->route('berkas_arsip');

        return [
            'kode_klasifikasi_id' => ['required', 'exists:klasifikasi_arsip,id'],
            'nomor_berkas' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('berkas_arsip', 'nomor_berkas')->ignore($berkasId),
            ],
            'uraian_berkas' => ['required', 'string', 'min:10'],
            'kurun_waktu' => ['nullable', 'string', 'max:100'],
            'tahun' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'unit_kerja' => ['required', 'string', 'max:150'],
            'status_arsip' => ['required', Rule::in(['Aktif', 'Inaktif', 'Permanen'])],
            'lokasi_arsip_id' => ['nullable', 'exists:lokasi_arsip,id'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'kode_klasifikasi_id' => 'kode klasifikasi',
            'nomor_berkas' => 'nomor berkas',
            'uraian_berkas' => 'uraian berkas',
            'kurun_waktu' => 'kurun waktu',
            'tahun' => 'tahun',
            'unit_kerja' => 'unit kerja',
            'status_arsip' => 'status arsip',
            'lokasi_arsip_id' => 'lokasi arsip',
            'keterangan' => 'keterangan',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'kode_klasifikasi_id.required' => 'Kode klasifikasi wajib dipilih.',
            'kode_klasifikasi_id.exists' => 'Kode klasifikasi tidak valid.',
            'nomor_berkas.unique' => 'Nomor berkas sudah digunakan.',
            'nomor_berkas.max' => 'Nomor berkas maksimal 100 karakter.',
            'uraian_berkas.required' => 'Uraian berkas wajib diisi.',
            'uraian_berkas.min' => 'Uraian berkas minimal 10 karakter.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun tidak valid.',
            'tahun.max' => 'Tahun tidak boleh lebih dari tahun depan.',
            'unit_kerja.required' => 'Unit kerja wajib diisi.',
            'unit_kerja.max' => 'Unit kerja maksimal 150 karakter.',
            'status_arsip.required' => 'Status arsip wajib dipilih.',
            'status_arsip.in' => 'Status arsip tidak valid.',
            'lokasi_arsip_id.exists' => 'Lokasi arsip tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
        ];
    }
}
