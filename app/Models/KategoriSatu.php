<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriSatu extends Model
{
    use HasFactory;

    protected $table = 'kategori_satu';
    protected $primaryKey = 'id_kategori1';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_kategori',
    ];

    // Relasi ke kategori dua (1 - n)
    public function kategoriDua()
    {
        return $this->hasMany(KategoriDua::class, 'id_kategori1', 'id_kategori1');
    }
}
