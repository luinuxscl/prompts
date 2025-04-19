<?php

namespace Luinuxscl\Prompts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Luinuxscl\Prompts\Models\Prompt create(string $name, string $content, string|null $description = null)
 * @method static bool delete(string $name)
 * @method static string|null render(string $name, int $depth = 0)
 * @method static \Luinuxscl\Prompts\Models\Prompt|null get(string $name)
 * @method static \Illuminate\Database\Eloquent\Collection all()
 * @method static \Luinuxscl\Prompts\Models\Prompt|null update(string $name, string $content, string|null $description = null)
 * 
 * @see \Luinuxscl\Prompts\Services\PromptService
 */
class Prompts extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'prompts';
    }
}
