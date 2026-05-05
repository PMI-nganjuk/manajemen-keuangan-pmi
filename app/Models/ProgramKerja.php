<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    use HasFactory;

    protected $table = 'program_kerja';
    protected $primaryKey = 'id_program_kerja';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_program',
        'keterangan',
        'id_pegawai',
    ];

    // 1 - n user (pegawai)
    public function pegawai()
    {
        return $this->belongsTo(User::class, 'id_pegawai', 'id_user');
    }

    //  1 - n pengeluaran kas
    public function pengeluaranKas()
    {
        return $this->hasMany(PengeluaranKas::class, 'id_program_kerja', 'id_program_kerja');
    }

    // 1 - n penerimaan kas
    public function penerimaanKas()
    {
        return $this->hasMany(PenerimaanKas::class, 'id_program_kerja', 'id_program_kerja');
    }

    // 1 - n penyesuaian
    public function penyesuaian()
    {
        return $this->hasMany(Penyesuaian::class, 'id_program_kerja', 'id_program_kerja');
    }

    // 1 - n GL
    public function gl()
    {
        return $this->hasMany(Gl::class, 'id_program_kerja', 'id_program_kerja');
    }

}
