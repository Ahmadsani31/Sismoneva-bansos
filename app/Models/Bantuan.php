<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Bantuan extends Model
{
    protected $table = 'bantuan';
    protected $fillable = [
        'user_id',
        'program_id',
        'jumlah',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'tanggal',
        'file_bukti',
        'file_type',
        'file_size',
        'keterangan',
        'status',
        'keterangan_status',
    ];

    public $timestamps = true;

    protected function fileBukti(): Attribute
    {
        return Attribute::make(
            get: fn($file_bukti) => url('/storage/' . $file_bukti),
        );
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
