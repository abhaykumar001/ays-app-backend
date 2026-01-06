<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name'];
    /**
     * The dates attributes
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
   
    // formatted created at accessor
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('F d, Y') : null;
    }
}
