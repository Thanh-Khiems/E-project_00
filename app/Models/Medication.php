<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $table = 'medications';

   protected $fillable = [
    'name',
    'dosage',
    'medicine_type_id',
    'category'
];

public function type()
{
    return $this->belongsTo(MedicineType::class, 'medicine_type_id');
}
}