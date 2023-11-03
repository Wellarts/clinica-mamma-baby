<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PlanejamentoReprodutivo extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [

        'paciente_id',
        'data_atendimento',
        'peso',
        'altura',
        'imc',
        'temp',
        'pa',
        'spo2',
        'fc',
        'queixa_principal',
        'historia_doenca',
        'menarca',
        'dum',
        'ciclo_mestrual',
        'smp',
        'metodo_contraceptivo',
        'corrimento',
        'corrimento_desc',
        'dispareunia',
        'gesta',
        'parto',
        'aborto',
        'gravidez_ectopica',
        'intercorrencias',
        'intercorrencias_desc',
        'primeiro_parto',
        'ultimo_parto',
        'aleitamento',
        'medicacao_uso',
        'medicacao_uso_desc',
        'tabagismo',
        'etilismo',
        'drogas',
        'sintomas_urinario',
        'sintomas_urinario_desc',
        'sintomas_intestinais',
        'sintomas_intestinais_desc',
        'cardiovasculares',
        'cardiovasculares_desc',
        'cardiovasculares_f',
        'cardiovasculares_f_desc',
        'endocrinas',
        'endocrinas_desc',
        'endocrinas_f',
        'endocrinas_f_desc',
        'alergias_desc',
        'alergias',
        'vacina_dt',
        'vacina_dt_data_1',
        'vacina_dt_data_2',
        'vacina_dt_data_3',
        'vacina_dt_reforco',
        'vacina_hpv',
        'vacina_hpv_data_1',
        'vacina_hpv_data_2',
        'vacina_hepatite_b',
        'vacina_hepatite_b_data_1',
        'vacina_hepatite_b_data_2',
        'vacina_hepatite_b_data_3',
        'ist_s',
        'ist_s_desc',
        'cirurgias_transfusao',
        'cirurgias_transfusao_desc',
        'cancer',
        'cancer_desc',
        'cancer_f',
        'cancer_f_desc',
        'outros_f',
        'outros_f_desc',
        'outros',
        'outros_desc',
        'mamografia',
        'preventivo',
        'data_mamografia',
        'data_preventivo',
        'evolucao',
        'diagnostico_intervencao_id',
        'planejamento_implementacao_id',
        'planejamento_desc',
        'avaliacao',
        'anexo_termo',
        'anexo_outros',
        'status',
    ];

    protected $casts = [
        'diagnostico_intervencao_id' => 'array',
        'planejamento_implementacao_id' => 'array',
        'anexo_termo' => 'array',
        'anexo_outros' => 'array',

    ];

    public function Paciente() {
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
