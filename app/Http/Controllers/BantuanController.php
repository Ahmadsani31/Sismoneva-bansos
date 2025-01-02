<?php

namespace App\Http\Controllers;

use App\Http\Requests\BantuanRequest;
use App\Http\Requests\BantuanStatusRequest;
use App\Models\Bantuan;
use App\Models\Program;
use App\Notifications\UpBansos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Elibyy\TCPDF\Facades\TCPDF;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Bantuan';
        if (Auth::user()->level == 1) {
            return view('v_bantuan-admin', compact('pageTitle'));
        } else {
            return view('v_bantuan', compact('pageTitle'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BantuanRequest $request)
    {
        try {
            if ($request->ID == 0) {
                $request->validate([
                    'file_bukti' => 'required|mimes:png,jpg,jpeg,pdf|max:2048',
                ]);

                $cekFile = $this->cekTheFile($request);

                $validate = $request->validated();
                $validate['user_id'] = Auth::user()->id;
                $validate['status'] = 'pending';
                $validate['file_bukti'] = $cekFile['file_path'];
                $validate['file_type'] = $cekFile['type'];
                $validate['file_size'] = $cekFile['size'];
                Bantuan::create($validate);
            } else {
                $cekFile = $this->cekTheFileUpdate($request);

                $validate = $request->validated();

                $validate['file_bukti'] = $cekFile['file_path'];
                $validate['file_type'] = $cekFile['type'];
                $validate['file_size'] = $cekFile['size'];

                Bantuan::where('id', $request->ID)->update($validate);
            }

            return response()->json(['param' => true, 'message' => 'Successfully']);
        } catch (\Exception $err) {
            return response()->json(['param' => false, 'message' => $err->getMessage()]);
        }
    }

    public function storeStatus(BantuanStatusRequest $request)
    {
        try {
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
            Bantuan::where('id', $request->ID)->update($request->validated());


            return response()->json(['param' => true, 'message' => 'Successfully']);
        } catch (\Exception $err) {
            return response()->json(['param' => false, 'message' => $err->getMessage()]);
        }
    }


    public function jumlah_perprogram()
    {
        $sql = DB::table('bantuan')
            ->select(DB::raw('SUM(jumlah) as jumlah'));

        if (Auth::user()->level == 2) {
            $sql->where('user_id', Auth::user()->id);
        }
        $sql->groupBy('program_id');

        $query = $sql->get();
        foreach ($query as $value) {

            $jumlah[] = $value->jumlah;
        }

        $series[] = [
            'name' => 'Bansos',
            'data' => $jumlah
        ];

        $program = Program::all()->pluck('nama');


        return response()->json(['param' => true, 'items' => [
            'series' => $series,
            'categories' => $program
        ]]);
    }

    public function jumlah_perwilayah()
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

        return response()->json(['param' => true, 'items' => [
            'series' => $series,
            'categories' => $categories
        ]]);
    }

    public function export_excel(Request $request)
    {
        $program_id = $request->program_id;
        $provinsi_id = $request->provinsi_id;
        $kabupaten_id = $request->kabupaten_id;
        $kecamatan_id = $request->kecamatan_id;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

        $sheet->setCellValue('A1', "SISTEM MONITORING DAN EVALUASI PROGRAM BANTUAN SOSIAL"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A2', "NO"); // Set kolom A3 dengan tulisan "NO"
        $sheet->setCellValue('B2', "Program"); // Set kolom B3 dengan tulisan "NIS"
        $sheet->setCellValue('C2', "Jumlah"); // Set kolom C3 dengan tulisan "NAMA"
        $sheet->setCellValue('D2', "Provinsi"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $sheet->setCellValue('E2', "Kabupaten"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $sheet->setCellValue('F2', "Kecamatan"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $sheet->setCellValue('G2', "Status"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('H2', "Tanggal"); // Set kolom E3 dengan tulisan "ALAMAT"
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A2')->applyFromArray($style_col);
        $sheet->getStyle('B2')->applyFromArray($style_col);
        $sheet->getStyle('C2')->applyFromArray($style_col);
        $sheet->getStyle('D2')->applyFromArray($style_col);
        $sheet->getStyle('E2')->applyFromArray($style_col);
        $sheet->getStyle('F2')->applyFromArray($style_col);
        $sheet->getStyle('G2')->applyFromArray($style_col);
        $sheet->getStyle('H2')->applyFromArray($style_col);


        $query = Bantuan::select('*');
        if (Auth::user()->level != 1) {
            $query->where('user_id', Auth::user()->id);
        }
        // dd($program_id);

        if ($program_id) {
            $query->where('program_id', $program_id);
        }
        if ($provinsi_id) {
            $query->where('provinsi_id', $provinsi_id);
        }
        if ($kabupaten_id) {
            $query->where('kabupaten_id', $kabupaten_id);
        }
        if ($kecamatan_id) {
            $query->where('kecamatan_id', $kecamatan_id);
        }
        $bantuan = $query->get();
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 3; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach (range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        foreach ($bantuan as $data) { // Lakukan looping pada variabel siswa

            $provinsi_id =  DB::table('indonesia_provinces')->select('name')->where('id', $data->provinsi_id)->pluck('name');
            $kabupaten_id =  DB::table('indonesia_cities')->select('name')->where('id', $data->kabupaten_id)->pluck('name');
            $kecamatan_id =  DB::table('indonesia_districts')->select('name')->where('id', $data->kecamatan_id)->pluck('name');

            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data->program->nama);
            $sheet->setCellValue('C' . $numrow, $data->jumlah);
            $sheet->setCellValue('D' . $numrow, $provinsi_id[0]);
            $sheet->setCellValue('E' . $numrow, $kabupaten_id[0]);
            $sheet->setCellValue('F' . $numrow, $kecamatan_id[0]);
            $sheet->setCellValue('G' . $numrow, $data->status);
            $sheet->setCellValue('H' . $numrow, $data->tanggal);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('H' . $numrow)->applyFromArray($style_row);

            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping

        }


        $sheet->setTitle("Laporan Data Bansos");

        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="data-bansos' . time() . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        // Write an .xlsx file
        $writer = new Xlsx($spreadsheet);

        // Save .xlsx file to the current directory
        $writer->save('php://output');
    }

    public function export_pdf(Request $request)
    {
        $program_id = $request->program_id;
        $provinsi_id = $request->provinsi_id;
        $kabupaten_id = $request->kabupaten_id;
        $kecamatan_id = $request->kecamatan_id;
        $query = Bantuan::select('*');
        if (Auth::user()->level != 1) {
            $query->where('user_id', Auth::user()->id);
        }

        if ($program_id) {
            $query->where('program_id', $program_id);
        }
        if ($provinsi_id) {
            $query->where('provinsi_id', $provinsi_id);
        }
        if ($kabupaten_id) {
            $query->where('kabupaten_id', $kabupaten_id);
        }
        if ($kecamatan_id) {
            $query->where('kecamatan_id', $kecamatan_id);
        }
        $bantuan = $query->get();

        $html = view()->make('pdf.v_bansos', ['bantuan' => $bantuan])->render();

        $pdf = new TCPDF;

        $pdf::SetTitle('DATA LAPORAN');

        $pdf::AddPage();

        $pdf::writeHTML($html, true, false, true, false, '');



        // $pdf::Output(public_path($filename), 'F');
        $pdf::Output('data_laporan_' . time() . '.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function showFile($id)
    {
        try {
            $bantuan = Bantuan::findOrFail(Crypt::decrypt($id));
            $file =  Storage::disk('public')->get($bantuan->file_bukti);
            $mimeType =  Storage::disk('public')->mimeType($bantuan->file_bukti);
            return response($file)->header('Content-Type', $mimeType);
        } catch (\Throwable $err) {
            abort(404);
        }
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
            return response()->json(['param' => false, 'message' => $err->getMessage()]);
        }
    }
}
