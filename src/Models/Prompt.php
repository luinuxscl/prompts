<?php

namespace Luinuxscl\Prompts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    use HasFactory;

    /**
     * Prompts que son padres de este prompt.
     */
    public function parents()
    {
        return $this->belongsToMany(
            self::class,
            'prompt_relations',
            'child_prompt_id',
            'parent_prompt_id'
        );
    }

    /**
     * Prompts que son hijos de este prompt.
     */
    public function children()
    {
        return $this->belongsToMany(
            self::class,
            'prompt_relations',
            'parent_prompt_id',
            'child_prompt_id'
        );
    }
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
        'is_system',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_system' => 'boolean',
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

    /**
     * Create a new system prompt.
     *
     * @param string $name
     * @param string $content
     * @param string|null $description
     * @return self
     */
    public static function createSystem(string $name, string $content, ?string $description = null): self
    {
        return static::create([
            'name' => $name,
            'content' => $content,
            'description' => $description,
            'is_system' => true,
        ]);
    }

    /**
     * Determine if the prompt is a system prompt.
     *
     * @return bool
     */
    public function isSystem(): bool
    {
        return (bool) $this->is_system;
    }
}
