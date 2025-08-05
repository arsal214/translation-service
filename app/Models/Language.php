<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['locale', 'name'];

    public function translations()
    {
        return $this->hasMany(Translation::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
