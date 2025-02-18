<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZscoreLakilaki extends Model
{
    use HasFactory;
    protected $table = 'z_score_laki';
    protected $fillable = [
        'length',
        'min_3_sd',
        'min_2_sd',
        'min_1_sd',
        'median',
        'plus_1_sd',
        'plus_2_sd',
        'plus_3_sd',
    ];
}
