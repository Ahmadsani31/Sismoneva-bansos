<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BantuanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $provinsi_id =  DB::table('indonesia_provinces')->select('name')->where('id', $this->provinsi_id)->pluck('name');
        $kabupaten_id =  DB::table('indonesia_cities')->select('name')->where('id', $this->kabupaten_id)->pluck('name');
        $kecamatan_id =  DB::table('indonesia_districts')->select('name')->where('id', $this->kecamatan_id)->pluck('name');


        return [
            'program_id' => $this->program->nama,
            'jumlah' => $this->jumlah,
            'provinsi_id' => $provinsi_id[0],
            'kabupaten_id' => $kabupaten_id[0],
            'kecamatan_id' => $kecamatan_id[0],
            'tanggal' => $this->tanggal,
            'keterangan' => $this->keterangan,
            'file_bukti' => $this->file_bukti,
        ];
    }
}
