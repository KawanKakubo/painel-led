<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'painel_id',
        'titulo',
        'descricao',
        'arquivo_original',
        'arquivo_processado',
        'status',
        'motivo_rejeicao',
        'moderador_id',
        'data_aprovacao',
        'data_rejeicao',
        'data_exibicao',
        'duracao_segundos',
        'vnnox_media_id',
        'vezes_exibido',
    ];

    protected $casts = [
        'data_aprovacao' => 'datetime',
        'data_rejeicao' => 'datetime',
        'data_exibicao' => 'datetime',
    ];

    /**
     * Cidadão que enviou o vídeo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Moderador que aprovou/rejeitou o vídeo
     */
    public function moderador()
    {
        return $this->belongsTo(User::class, 'moderador_id');
    }

    /**
     * Painel onde o vídeo será/foi exibido
     */
    public function painel()
    {
        return $this->belongsTo(Painel::class);
    }

    /**
     * Histórico de exibições deste vídeo
     */
    public function historicoExibicoes()
    {
        return $this->hasMany(HistoricoExibicao::class);
    }

    /**
     * Scope para vídeos pendentes de moderação
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para vídeos aprovados
     */
    public function scopeAprovados($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope para vídeos do cidadão
     */
    public function scopeDoUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Aprova o vídeo
     */
    public function aprovar($moderadorId, $painelId = null)
    {
        $this->update([
            'status' => 'approved',
            'moderador_id' => $moderadorId,
            'data_aprovacao' => now(),
            'painel_id' => $painelId ?? $this->painel_id,
        ]);
    }

    /**
     * Rejeita o vídeo
     */
    public function rejeitar($moderadorId, $motivo)
    {
        $this->update([
            'status' => 'rejected',
            'moderador_id' => $moderadorId,
            'data_rejeicao' => now(),
            'motivo_rejeicao' => $motivo,
        ]);
    }

    /**
     * Marca como exibido
     */
    public function marcarComoExibido()
    {
        $this->increment('vezes_exibido');
        
        if ($this->status === 'approved') {
            $this->update([
                'status' => 'displayed',
                'data_exibicao' => now(),
            ]);
        }
    }

    /**
     * Retorna a duração formatada
     */
    public function getDuracaoFormatadaAttribute()
    {
        if (!$this->duracao_segundos) {
            return null;
        }

        $minutos = floor($this->duracao_segundos / 60);
        $segundos = $this->duracao_segundos % 60;

        return sprintf('%02d:%02d', $minutos, $segundos);
    }
}
