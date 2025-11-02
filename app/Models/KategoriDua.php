<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriDua extends Model
{
    use HasFactory;

    protected $table = 'kategori_dua';
    protected $primaryKey = 'id_kategori2';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_kategori',
        'id_kategori1',
        'id_coa',
    ];

    // n - 1 KategoriSatu
    public function kategoriSatu()
    {
        return $this->belongsTo(KategoriSatu::class, 'id_kategori1', 'id_kategori1');
    }

    // n - 1 Coa
    public function coa()
    {
        return $this->belongsTo(Coa::class, 'id_coa', 'id_coa');
    }
}
