<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonConformity extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'timeline',
        'followup_date',
        'response_details',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
