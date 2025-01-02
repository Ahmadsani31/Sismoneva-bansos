<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BantuanRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'program_id' => 'required',
            'jumlah' => 'required',
            'provinsi_id' => 'required|integer',
            'kabupaten_id' => 'required|integer',
            'kecamatan_id' => 'required|integer',
            'tanggal' => 'required',
            'keterangan' => '',
        ];
    }
}
