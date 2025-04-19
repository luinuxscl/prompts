<?php

namespace Luinuxscl\Prompts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'prompts';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'content',
    ];

    /**
     * Buscar un prompt por su nombre.
     *
     * @param string $name
     * @return self|null
     */
    public static function findByName(string $name): ?self
    {
        return static::where('name', $name)->first();
    }
}
