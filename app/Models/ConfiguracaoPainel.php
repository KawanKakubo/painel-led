<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoPainel extends Model
{
    use HasFactory;

    protected $table = 'configuracoes_painel';

    protected $fillable = [
        'nome',
        'vnnox_app_key',
        'vnnox_app_secret',
        'vnnox_api_url',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    protected $hidden = [
        'vnnox_app_secret',
    ];

    /**
     * Retorna a configuração ativa principal
     */
    public static function getAtiva()
    {
        return self::where('ativo', true)->first();
    }
}
