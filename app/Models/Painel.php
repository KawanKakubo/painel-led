<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Painel extends Model
{
    use HasFactory;

    protected $table = 'paineis';

    protected $fillable = [
        'player_id',
        'nome',
        'localizacao',
        'resolucao_largura',
        'resolucao_altura',
        'ativo',
        'online',
        'ultimo_heartbeat',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'online' => 'boolean',
        'ultimo_heartbeat' => 'datetime',
    ];

    /**
     * Vídeos associados a este painel
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Histórico de exibições deste painel
     */
    public function historicoExibicoes()
    {
        return $this->hasMany(HistoricoExibicao::class);
    }

    /**
     * Retorna a resolução formatada
     */
    public function getResolucaoAttribute()
    {
        if ($this->resolucao_largura && $this->resolucao_altura) {
            return "{$this->resolucao_largura}x{$this->resolucao_altura}";
        }
        return null;
    }

    /**
     * Verifica se o painel está online (baseado no último heartbeat)
     */
    public function isOnline()
    {
        if (!$this->ultimo_heartbeat) {
            return false;
        }
        
        // Considera online se o último heartbeat foi nos últimos 5 minutos
        return $this->ultimo_heartbeat->diffInMinutes(now()) <= 5;
    }
}
