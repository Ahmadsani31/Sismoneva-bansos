<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\BantuanRequest;
use App\Http\Resources\BantuanResource;
use App\Models\Bantuan;
use App\Notifications\UpBansos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BantuanController extends BaseController
{
    public function index()
    {
        try {
            $bantuan =    BantuanResource::collection(Bantuan::all());
            return $this->sendResponse($bantuan, 'successfully.');
        } catch (\Throwable $err) {
            return $this->sendError($err->getMessage(), ['error' => 'data not found']);
        }
    }

    public function storeStatus(Request $request)
    {
        try {

            $request->validate([
                'status' => 'required',
            ]);

            if ($request->status == 'ditolak') {
                $request->validate([
                    'keterangan_status' => 'required',
                ]);
            }

            $bantuan = Bantuan::with('user')->find($request->ID);

            $messages["user"] = "Halo {$bantuan->user->name}";
            $messages["title"] = "Admin, baru saya memperbarui status bantuan sosial kamu";
            $messages["status"] = 'Status : ' . Str::upper($request->status);
            if ($request->keterangan_status) {
                $messages["body"] = 'Keterangan : ' . $request->keterangan_status;
            } else {
                $messages["body"] = "";
            }

            $bantuan->user->notify(new UpBansos($messages));
            Bantuan::where('id', $request->ID)->update([
                'status' => $request->status,
                'keterangan_status' => $request->keterangan_status,
            ]);

            return response()->json(['param' => true, 'message' => 'Successfully']);
        } catch (\Exception $err) {
            return response()->json(['param' => false, 'message' => $err->getMessage()]);
        }
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'program_id' => 'required',
                'jumlah' => 'required',
                'provinsi_id' => 'required|integer',
                'kabupaten_id' => 'required|integer',
                'kecamatan_id' => 'required|integer',
                'tanggal' => 'required',
                'keterangan' => '',
            ]);
            if ($request->ID == 0) {

                $request->validate([
                    'file_bukti' => 'required|mimes:png,jpg,jpeg,pdf|max:2048',
                ]);

                $cekFile = $this->cekTheFile($request);

                $validate = $request->input();
                $validate['user_id'] = Auth::user()->id;
                $validate['status'] = 'pending';
                $validate['file_bukti'] = $cekFile['file_path'];
                $validate['file_type'] = $cekFile['type'];
                $validate['file_size'] = $cekFile['size'];
                Bantuan::create($validate);
            } else {
                $cekFile = $this->cekTheFileUpdate($request);

                $validate = $request->input();

                $validate['file_bukti'] = $cekFile['file_path'];
                $validate['file_type'] = $cekFile['type'];
                $validate['file_size'] = $cekFile['size'];

                Bantuan::where('id', $request->ID)->update($validate);
            }

            return $this->sendResponse([], 'save or update successfully.');
        } catch (\Exception $err) {
            return $this->sendError($err->getMessage(), ['error' => 'something error']);
        }
    }


    public function statistik_laporan()
    {
        $sql = DB::table('bantuan')
            ->select(DB::raw('SUM(bantuan.jumlah) as jumlah'), 'indonesia_provinces.name as name')
            ->join('indonesia_provinces', 'bantuan.provinsi_id', '=', 'indonesia_provinces.id');

        if (Auth::user()->level == 2) {
            $sql->where('user_id', Auth::user()->id);
        }
        $query = $sql->groupBy('bantuan.provinsi_id')
            ->get();

        foreach ($query as $value) {
            $categories[] = $value->name;
            $jumlah[] = $value->jumlah;
        }

        $series[] = [
            'name' => 'Bansos',
            'data' => $jumlah
        ];

        return $this->sendResponse([
            'series' => $series,
            'categories' => $categories
        ], 'save or update successfully.');
    }

    private function cekTheFile($request)
    {
        if ($request->hasFile('file_bukti')) {

            $file = $request->file('file_bukti');

            $size = $file->getSize();
            $type = $file->getClientOriginalExtension();
            $file_path = $file->store('bantuan', 'public');

            return [
                'size' => $size,
                'type' => $type,
                'file_path' => $file_path,
            ];
        } else {
            return [
                'size' => '',
                'type' => '',
                'file_path' => '',
            ];
        }
    }

    private function cekTheFileUpdate($request)
    {
        try {
            $bantuan = Bantuan::findOrFail($request->ID);
            if ($request->hasFile('file_bukti')) {

                if (Storage::disk('public')->exists($bantuan->file_bukti)) {
                    Storage::disk('public')->delete($bantuan->file_bukti);
                }

                $file = $request->file('file_bukti');

                $size = $file->getSize();
                $type = $file->getClientOriginalExtension();
                $file_path = $file->store('bantuan', 'public');

                return [
                    'size' => $size,
                    'type' => $type,
                    'file_path' => $file_path,
                ];
            } else {
                return [
                    'size' => $bantuan->file_size,
                    'type' => $bantuan->file_type,
                    'file_path' => $bantuan->file_bukti,
                ];
            }
        } catch (\Throwable $err) {
            return $this->sendError($err->getMessage(), ['error' => 'something error']);
        }
    }
}
