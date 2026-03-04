<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoExibicao extends Model
{
    use HasFactory;

    protected $table = 'historico_exibicoes';

    protected $fillable = [
        'video_id',
        'painel_id',
        'data_hora_inicio',
        'data_hora_fim',
        'exibicao_completa',
        'observacoes',
    ];

    protected $casts = [
        'data_hora_inicio' => 'datetime',
        'data_hora_fim' => 'datetime',
        'exibicao_completa' => 'boolean',
    ];

    /**
     * Vídeo que foi exibido
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Painel onde foi exibido
     */
    public function painel()
    {
        return $this->belongsTo(Painel::class);
    }
}
