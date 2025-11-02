<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPmi extends Model
{
    use HasFactory;

    protected $table = 'profil_pmi';
    protected $primaryKey = 'id_profil';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_pmi',
        'alamat',
        'ketua',
        'kepala_markas',
        'kepala_uud',
        'bendahara_markas',
        'bendahara_uud',
        'periode_buku_awal',
        'periode_buku_akhir',
        'tahun_buku',
    ];
}
