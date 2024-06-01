<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'contact';

    protected $fillable = [
        'created_at', 'updated_at', 'deleted_at', 'cle', 'organisation_id', 'e_mail', 'nom', 'prenom', 'telephone_fixe', 'service', 'fonction'
    ];

    protected $dates = ['deleted_at'];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
