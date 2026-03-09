<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'status_code',
        'response_time',
        'is_healthy',
        'error_message'
    ];

    public function domain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}
