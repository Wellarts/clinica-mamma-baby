<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Exame extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nome',

    ];

    public function Perinatal() {
        return $this->belongsTo(Perinatal::class);
    }

    public function ExamePerinatal() {
        return $this->hasMany(ExamePerinatal::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
