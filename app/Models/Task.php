<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Task merepresentasikan satu tugas dalam aplikasi.
 *
 * @property int    $id
 * @property string $title        Judul tugas
 * @property string $description  Deskripsi tugas (opsional)
 * @property bool   $is_completed Status apakah tugas sudah selesai
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Task extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'title',
        'description',
        'is_completed',
    ];

    /**
     * Casting tipe data untuk atribut tertentu.
     * is_completed otomatis dikonversi ke boolean.
     */
    protected $casts = [
        'is_completed' => 'boolean',
    ];

    /**
     * Scope query untuk tugas yang belum selesai.
     */
    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Scope query untuk tugas yang sudah selesai.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope query untuk pencarian berdasarkan title.
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('title', 'like', "%{$keyword}%");
    }
}