<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PlanejamentoImplementacao extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'descricao',
         'tipo',
     ];

     public function PlanejamentoGinecologico() {
        $this->hasMany(PlanejamentoGinecologico::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*'])
        ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }
}
