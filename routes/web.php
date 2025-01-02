<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PengajianController;
use App\Http\Controllers\PosisiController;
use App\Http\Controllers\PotonganController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\TabungController;
use App\Http\Controllers\TunjanganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WilayahController;
use App\Http\Middleware\XSSProtection;
use App\Models\Bantuan;
use App\Models\HistoriPenempatan;
use App\Models\Karyawan;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use function Laravel\Prompts\error;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     echo 'dashboard';
// });



Route::middleware(['auth'])->group(function () {

    Route::get('program', [ProgramController::class, 'index'])->name('program');
    Route::post('program', [ProgramController::class, 'store'])->name('program.store')->middleware(XSSProtection::class);

    Route::get('bantuan', [BantuanController::class, 'index'])->name('bantuan');
    Route::post('bantuan', [BantuanController::class, 'store'])->name('bantuan.store')->middleware(XSSProtection::class);
    Route::post('bantuan/status', [BantuanController::class, 'storeStatus'])->name('bantuan.store-status')->middleware(XSSProtection::class);
    Route::get('bantuan/preview/{id}', [BantuanController::class, 'showFile'])->name('bantuan.file');
    Route::post('bantuan/excel', [BantuanController::class, 'export_excel'])->name('bantuan.excel');
    Route::post('bantuan/pdf', [BantuanController::class, 'export_pdf'])->name('bantuan.pdf');

    Route::get('bantuan/per-program', [BantuanController::class, 'jumlah_perprogram'])->name('bantuan.program');
    Route::get('bantuan/per-wilayah', [BantuanController::class, 'jumlah_perwilayah'])->name('bantuan.wilayah');

    Route::get('provinces', [WilayahController::class, 'provinces'])->name('provinces');
    Route::get('cities', [WilayahController::class, 'cities'])->name('cities');
    Route::get('districts', [WilayahController::class, 'districts'])->name('districts');
    Route::get('villages', [WilayahController::class, 'villages'])->name('villages');

    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store')->middleware(XSSProtection::class);
    Route::post('/user/password', [UserController::class, 'updatePassword'])->name('user.password')->middleware(XSSProtection::class);
    Route::get('/user/logout-all', [UserController::class, 'logoutAllUsers']);
    Route::get('/user/logout/{id}', [UserController::class, 'logoutUser']);

    Route::get('/', [DashboardController::class, 'index']);
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/datatable/{tabel}', [DatatableController::class, 'index'])->name('datatable');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware(XSSProtection::class);

    Route::delete('/delete/{table}/{id}', [DeleteController::class, 'index'])->name('delete');
    Route::post('/modal/{name}', function (Request $request) {
        $param = $request->segment(2);
        return view('modal/' . $param);
    })->middleware(XSSProtection::class);
});



Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::get('user-notify', [UserController::class, 'send_mail']);

Route::get('/sql', function (Request $request) {

    $spreadsheet = new Spreadsheet();
    // Retrieve the current active worksheet
    $sheet = $spreadsheet->getActiveSheet();

    // Set the value of cell A1
    $sheet->setCellValue('A1', 'GeeksForGeeks!');

    // Sets the value of cell B1
    $sheet->setCellValue('A2', 'A Computer Science Portal For Geeks');

    // Proses file excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Data Siswa.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');

    // Write an .xlsx file
    $writer = new Xlsx($spreadsheet);

    // Save .xlsx file to the current directory
    $writer->save('php://output');
});
