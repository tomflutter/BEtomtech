<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int   
 * @property string 
 * @property string 
 * @property bool  
 * @property \Carbon\Carbon 
 * @property \Carbon\Carbon
 */
class Task extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'title',
        'description',
        'is_completed',
    ];

   
    protected $casts = [
        'is_completed' => 'boolean',
    ];

    
    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }

   
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where('title', 'like', "%{$keyword}%");
    }
}