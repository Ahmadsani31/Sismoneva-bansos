<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Bantuan;
use App\Models\GajiKaryawan;
use App\Models\HistoriPenempatan;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Mitra;
use App\Models\Posisi;
use App\Models\Potongan;
use App\Models\Program;
use App\Models\Tabung;
use App\Models\Tunjangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DatatableController extends Controller
{
    public function index(Request $request, $tabel)
    {
        if ($request->ajax()) {
            switch ($tabel) {
                case 'user':
                    $data = User::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->addColumn('aktif', function ($row) {
                            if ($row->level == 1) {
                                $output = '<span class="badge p-2 bg-success">Admin</span><br/>';
                            } else {
                                $output = '<span class="badge p-2 bg-info">Operator</span><br/>';
                            }
                            if ($row->aktif == 'Y') {
                                $output .= '<span class="badge p-1 bg-primary">Aktif</span>';
                            } else {
                                $output .= '<span class="badge p-1 bg-danger">Non Aktif</span>';
                            }
                            return $output;
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre text-warning" id="user-password" parent="' . $row->id . '" judul="Edit Password"><i class="fa-solid fa-key fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre text-success" id="user-edit" parent="' . $row->id . '" judul="Edit User"><i class="fa-solid fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-sm btn-outline m-1 text-danger" onclick="logOutUser(' . $row->id . ')"><i class="fa-solid fa-right-from-bracket fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'bahan_baku':
                    $data = BahanBaku::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->editColumn('jenis_bahan', function ($row) {
                            $jenis_bahan = DB::table('jenis_bahan')->find($row->jenis_bahan);
                            return $jenis_bahan->nama;
                        })
                        ->editColumn('satuan_bahan', function ($row) {
                            $satuan_bahan = DB::table('satuan_bahan')->find($row->satuan_bahan);
                            return $satuan_bahan->nama;
                        })
                        ->editColumn('jumlah', function ($row) {
                            $satuan_bahan = DB::table('satuan_bahan')->find($row->satuan_bahan);
                            return number_format($row->jumlah) . ' ' . $satuan_bahan->nama;
                        })
                        ->addColumn('kadaluarsa', function ($row) {
                            return Carbon::create($row->kadaluarsa)->format('d F Y');
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="bahan-baku" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="bahan_baku" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'karyawan':
                    $data = Karyawan::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->editColumn('phone', function ($row) {
                            return '+62 ' . $row->phone;
                        })
                        ->editColumn('tanggal_lahir', function ($row) {
                            return Carbon::create($row->tanggal_lahir)->format('d F Y');
                        })
                        ->addColumn('action', function ($row) {

                            $btn = '<a href="' . route('karyawan.detail', ['slug' => $row->slug]) . '" target="_blank" class="btn btn-sm btn-outline m-1"><i class="fa-solid text-success fa-circle-exclamation fa-xl"></i></a>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="karyawan" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'potongan':
                    $data = Potongan::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->editColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->editColumn('harga', function ($row) {
                            return 'Rp. ' . number_format($row->harga);
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="potongan" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="potongan" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'tunjangan':
                    $data = Tunjangan::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->editColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->editColumn('harga', function ($row) {
                            return 'Rp. ' . number_format($row->harga);
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="tunjangan" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="tunjangan" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'jabatan':
                    $data = Jabatan::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="jabatan" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="jabatan" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'posisi':
                    $data = Posisi::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('jabatan_id', function ($row) {
                            return $row->jabatan->nama;
                        })
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="posisi" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="posisi" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'penempatan':
                    $user = $request->input('user');
                    $data = HistoriPenempatan::where('karyawan_id', $user)->select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('karyawan_id', function ($row) {
                            return $row->karyawan->nama;
                        })
                        ->addColumn('posisi_id', function ($row) {
                            return $row->posisi->nama;
                        })
                        ->addColumn('jabatan_id', function ($row) {
                            return $row->jabatan->nama;
                        })
                        ->addColumn('tanggal_masuk', function ($row) {
                            return Carbon::create($row->tanggal_masuk)->format('d F Y');
                        })
                        ->addColumn('tanggal_selesai', function ($row) {
                            return Carbon::create($row->tanggal_selesai)->format('d F Y');
                        })
                        ->addColumn('status', function ($row) {
                            return $row->status;
                        })
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="posisi" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="posisi" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'gaji-tunjangan':
                    $id = $request->input('id');
                    // dd($id);
                    $data = DB::table('master_gaji_tunjangan')->select('tunjangan.nama', 'tunjangan.harga', 'master_gaji_tunjangan.id')
                        ->join('tunjangan', 'master_gaji_tunjangan.tunjangan_id', '=', 'tunjangan.id')
                        ->where('master_gaji_tunjangan.master_gaji_id', $id);
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->editColumn('harga', function ($row) {
                            return 'Rp. ' . number_format($row->harga);
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="posisi" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'gaji-potongan':
                    $id = $request->input('id');
                    $data = DB::table('master_gaji_potongan')->select('potongan.nama', 'potongan.harga', 'master_gaji_potongan.id')
                        ->join('potongan', 'master_gaji_potongan.potongan_id', '=', 'potongan.id')
                        ->where('master_gaji_potongan.master_gaji_id', $id);
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->editColumn('harga', function ($row) {
                            return 'Rp. ' . number_format($row->harga);
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="posisi" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'gaji_karyawan':
                    $data = GajiKaryawan::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="posisi" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="posisi" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'mitra':
                    $data = Mitra::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="mitra" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="mitra" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['aktif', 'action'])
                        ->toJson();
                    break;
                case 'bantuan':

                    $program_id = $request->get('program_id');
                    $provinsi_id = $request->get('provinsi_id');
                    $kabupaten_id = $request->get('kabupaten_id');
                    $kecamatan_id = $request->get('kecamatan_id');

                    $data = Bantuan::select('*');
                    if (Auth::user()->level != 1) {
                        $data->where('user_id', Auth::user()->id);
                    }
                    if ($program_id) {
                        $data->where('program_id', $program_id);
                    }
                    if ($provinsi_id) {
                        $data->where('provinsi_id', $provinsi_id);
                    }
                    if ($kabupaten_id) {
                        $data->where('kabupaten_id', $kabupaten_id);
                    }
                    if ($kecamatan_id) {
                        $data->where('kecamatan_id', $kecamatan_id);
                    }

                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->editColumn('program_id', function ($row) {
                            return  $row->program->nama;
                        })
                        ->addColumn('tanggal', function ($row) {
                            return Carbon::create($row->tanggal)->format('d F Y');
                        })
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->editColumn('file_bukti', function ($row) {
                            return  '<span class="badge p-2 bg-info modal-cre" id="image" parent="' . $row->id . '" judul="Preview File"><i class="fa-solid fa-file-circle-exclamation fa-xl"></i></span>';
                        })
                        ->addColumn('provinsi', function ($row) {

                            $provinsi_id =  DB::table('indonesia_provinces')->select('name')->where('id', $row->provinsi_id)->get()->pluck('name');
                            $kabupaten_id =  DB::table('indonesia_cities')->select('name')->where('id', $row->kabupaten_id)->get()->pluck('name');
                            $kecamatan_id =  DB::table('indonesia_districts')->select('name')->where('id', $row->kecamatan_id)->get()->pluck('name');
                            $status = '<p class="m-0">' . $provinsi_id[0] . '</p>';
                            $status .= '<p class="m-0">' . $kabupaten_id[0] . '</p>';
                            $status .= '<p class="m-0">' . $kecamatan_id[0] . '</p>';
                            return  $status;
                        })
                        ->editColumn('status', function ($row) {
                            if ($row->status == 'pending') {
                                $status = '<span class="badge bg-warning text-white p-1">Pending</span>';
                            } elseif ($row->status == 'disetujui') {
                                $status = '<span class="badge bg-success text-white p-1">Disetujui</span>';
                            } else {
                                $status = '<span class="badge bg-danger text-white p-1">Ditolak</span>';
                            }
                            return  $status;
                        })
                        ->editColumn('keterangan', function ($row) {
                            return  $row->keterangan ?? '-';
                        })
                        ->addColumn('action', function ($row) {
                            if (Auth::user()->level == 1) {
                                $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="bantuan-detail" parent="' . $row->id . '"><i class="fa-solid text-info fa-eye fa-xl"></i></button>';
                                $btn .= '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="status" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                                $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="bantuan" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            } else {
                                if ($row->status == 'pending') {
                                    $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="bantuan" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                                    $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="bantuan" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                                } else {
                                    $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="bantuan-detail" parent="' . $row->id . '"><i class="fa-solid text-info fa-eye fa-xl"></i></button>';
                                }
                            }

                            return $btn;
                        })
                        ->rawColumns(['file_bukti', 'provinsi', 'status', 'action'])
                        ->toJson();
                    break;
                case 'program':
                    $data = Program::select('*');
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('created_at', function ($row) {
                            return Carbon::create($row->created_at)->format('d F Y');
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<button type="button" class="btn btn-sm btn-outline m-1 modal-cre" id="program" parent="' . $row->id . '"><i class="fa-solid text-primary fa-pen-to-square fa-xl"></i></button>';
                            $btn .= '<button type="button" class="btn btn-outline btn-sm m-1 modal-del" tabel="program" id="' . $row->id . '"><i class="fa-solid text-danger fa-trash-can fa-xl"></i></button>';
                            return $btn;
                        })
                        ->rawColumns(['created_at', 'action'])
                        ->toJson();
                    break;
                default:
                    return response()->json([
                        'draw' => 0,
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => []
                    ]);
                    break;
            }
        }
    }
}
