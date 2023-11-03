<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ContasReceber extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'paciente_id',
        'parcelas',
        'ordem_parcela',
        'formaPgmto',
        'data_vencimento',
        'data_recebimento',
        'status',
        'valor_total',
        'valor_parcela',
        'valor_recebido',
        'obs'
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
