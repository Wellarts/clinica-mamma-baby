<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Paciente extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nome',
        'data_nascimento',
        'endereco',
        'estado_id',
        'cidade_id',
        'profissão',
        'telefone',
        'cor',
        'obs',
    ];

    public function Estado() {
        return $this->belongsTo(Estado::class);
    }

    public function Cidade() {
        return $this->belongsTo(Cidade::class);
    }

    public function PlanejamentoReprodutivo() {
        return $this->hasMany(PlanejamentoReprodutivo::class);
    }

    public function PlanejamentoGinecologico() {
        return $this->hasMany(PlanejamentoGinecologico::class);
    }

    public function Perinatal() {
        return $this->hasMany(Perinatal::class);
    }

    public function ContasReceber() {
        return $this->hasMany(ContasReceber::class);
    }

    public function Amamentacao() {
        return $this->hasMany(Amamentacao::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }

}
