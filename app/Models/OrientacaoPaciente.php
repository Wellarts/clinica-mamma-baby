<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrientacaoPaciente extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'paciente_id',
        'data_orientacao',
        'descricao',

    ];

    public function Paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
