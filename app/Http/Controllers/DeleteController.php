<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Bantuan;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Mitra;
use App\Models\Posisi;
use App\Models\Potongan;
use App\Models\Program;
use App\Models\Tunjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteController extends Controller
{
    public function index($tabel, $id)
    {
        if (!empty($tabel)) {

            switch ($tabel) {
                case 'program':
                    try {
                        $data = Program::findOrFail($id);
                        $data->delete();
                        return response()->json(['param' => true, 'message' => 'Data Berhasil Dihapus']);
                    } catch (\Exception $err) {
                        return response()->json(['param' => false, 'message' => $err->getMessage()]);
                    }
                    break;

                case 'bantuan':
                    try {
                        $data = Bantuan::findOrFail($id);
                        if (Storage::disk('public')->exists($data->file_bukti)) {
                            Storage::disk('public')->delete($data->file_bukti);
                        }
                        $data->delete();
                        return response()->json(['param' => true, 'message' => 'Data Berhasil Dihapus']);
                    } catch (\Exception $err) {
                        return response()->json(['param' => false, 'message' => $err->getMessage()]);
                    }
                    break;
                case 'potongan':
                    try {
                        $data = Potongan::findOrFail($id);
                        $data->delete();
                        return response()->json(['param' => true, 'message' => 'Data Berhasil Dihapus']);
                    } catch (\Exception $err) {
                        return response()->json(['param' => false, 'message' => $err->getMessage()]);
                    }
                    break;
                case 'tunjangan':
                    try {
                        $data = Tunjangan::findOrFail($id);
                        $data->delete();
                        return response()->json(['param' => true, 'message' => 'Data Berhasil Dihapus']);
                    } catch (\Exception $err) {
                        return response()->json(['param' => false, 'message' => $err->getMessage()]);
                    }
                    break;
                case 'jabatan':
                    try {
                        $data = Jabatan::findOrFail($id);
                        $data->delete();
                        return response()->json(['param' => true, 'message' => 'Data Berhasil Dihapus']);
                    } catch (\Exception $err) {
                        return response()->json(['param' => false, 'message' => $err->getMessage()]);
                    }
                    break;
                case 'posisi':
                    try {
                        $data = Posisi::findOrFail($id);
                        $data->delete();
                        return response()->json(['param' => true, 'message' => 'Data Berhasil Dihapus']);
                    } catch (\Exception $err) {
                        return response()->json(['param' => false, 'message' => $err->getMessage()]);
                    }
                    break;
                case 'mitra':
                    try {
                        $data = Mitra::findOrFail($id);
                        $data->delete();
                        return response()->json(['param' => true, 'message' => 'Data Berhasil Dihapus']);
                    } catch (\Exception $err) {
                        return response()->json(['param' => false, 'message' => $err->getMessage()]);
                    }
                    break;
                default:
                    return response()->json(['param' => false, 'message' => 'Settingan untuk delete blum ada']);
                    break;
            }
        }
    }
}
