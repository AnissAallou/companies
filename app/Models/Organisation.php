<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisation extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'organisation';

    protected $fillable = [
        'created_at', 'updated_at', 'deleted_at', 'cle', 'nom', 'adresse', 'code_postal', 'ville', 'statut'
    ];

    protected $dates = ['deleted_at'];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
