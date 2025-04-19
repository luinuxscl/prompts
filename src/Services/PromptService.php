<?php

namespace Luinuxscl\Prompts\Services;

use Luinuxscl\Prompts\Models\Prompt;
use Illuminate\Support\Facades\Log;

class PromptService
{
    /**
     * Profundidad máxima de anidamiento permitida.
     *
     * @var int
     */
    protected $maxNestingDepth;

    /**
     * Patrón para reconocer prompts anidados.
     *
     * @var string
     */
    protected $nestingPattern;

    /**
     * Crear una nueva instancia del servicio.
     *
     * @return void
     */
    public function __construct()
    {
        $this->maxNestingDepth = config('prompts.max_nesting_depth', 5);
        $this->nestingPattern = config('prompts.nesting_pattern', '{{%s}}');
    }

    /**
     * Normaliza el nombre de un prompt (minúsculas, espacios a guiones bajos, recortar espacios).
     *
     * @param string $name
     * @return string
     */
    protected function normalizeName(string $name): string
    {
        // Recortar espacios y convertir a minúsculas
        $name = strtolower(trim($name));
        
        // Reemplazar espacios por guiones bajos
        $name = str_replace(' ', '_', $name);
        
        return $name;
    }

    /**
     * Crear un nuevo prompt.
     *
     * @param string $name
     * @param string $content
     * @param string|null $description
     * @return \Luinuxscl\Prompts\Models\Prompt
     */
    public function create(string $name, string $content, ?string $description = null)
    {
        $normalizedName = $this->normalizeName($name);
        
        return Prompt::updateOrCreate(
            ['name' => $normalizedName],
            [
                'content' => $content,
                'description' => $description,
            ]
        );
    }

    /**
     * Eliminar un prompt por su nombre.
     *
     * @param string $name
     * @return bool
     */
    public function delete(string $name): bool
    {
        $normalizedName = $this->normalizeName($name);
        $prompt = Prompt::findByName($normalizedName);
        
        if (!$prompt) {
            return false;
        }
        
        return $prompt->delete();
    }

    /**
     * Renderizar un prompt con variables y anidamiento resuelto.
     *
     * @param string $name
     * @param array $variables Variables a reemplazar en el formato ::variable::
     * @param int $depth Profundidad actual de anidamiento (para control interno)
     * @return string|null
     */
    public function render(string $name, array $variables = [], int $depth = 0): ?string
    {
        // Control de profundidad máxima para evitar bucles infinitos
        if ($depth >= $this->maxNestingDepth) {
            Log::warning("Prompts: Se alcanzó la profundidad máxima de anidamiento ($this->maxNestingDepth) al renderizar '$name'");
            return null;
        }

        // Normalizar el nombre antes de buscar
        $normalizedName = $this->normalizeName($name);
        
        // Obtener el prompt
        $prompt = Prompt::findByName($normalizedName);
        
        if (!$prompt) {
            Log::warning("Prompts: No se encontró el prompt '$normalizedName'");
            return null;
        }

        // Obtener el contenido del prompt
        $content = $prompt->content;
        
        // Procesar variables si existen
        if (!empty($variables)) {
            // Patrón para encontrar variables en formato ::variable::
            $variablePattern = '/::([A-Za-z0-9_\-]+)::/'; 
            preg_match_all($variablePattern, $content, $variableMatches);
            
            if (!empty($variableMatches[1])) {
                foreach ($variableMatches[1] as $varName) {
                    if (isset($variables[$varName])) {
                        $content = str_replace('::' . $varName . '::', $variables[$varName], $content);
                    }
                }
            }
        }

        // Buscar referencias a otros prompts (permitiendo espacios, guiones, mayúsculas)
        $pattern = '/\{\{\s*([A-Za-z0-9_\- ]+)\s*\}\}/'; // Patrón que permite espacios y cualquier carácter válido
        preg_match_all($pattern, $content, $matches);

        if (!empty($matches[1])) {
            // Resolver cada referencia anidada
            foreach ($matches[1] as $nestedPromptName) {
                // Normalizar el nombre del prompt anidado antes de buscarlo
                $normalizedNestedName = $this->normalizeName($nestedPromptName);
                // Pasar las variables al renderizado recursivo para mantenerlas disponibles
                $nestedContent = $this->render($normalizedNestedName, $variables, $depth + 1);
                
                if ($nestedContent !== null) {
                    // Buscar todas las variantes del mismo prompt (con/sin espacios)
                    $variantPattern = '/\{\{\s*' . preg_quote($nestedPromptName, '/') . '\s*\}\}/'; 
                    $content = preg_replace(
                        $variantPattern,
                        $nestedContent,
                        $content
                    );
                }
            }
        }

        return $content;
    }

    /**
     * Obtener un prompt por su nombre.
     *
     * @param string $name
     * @return \Luinuxscl\Prompts\Models\Prompt|null
     */
    public function get(string $name): ?Prompt
    {
        $normalizedName = $this->normalizeName($name);
        return Prompt::findByName($normalizedName);
    }

    /**
     * Obtener todos los prompts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Prompt::all();
    }

    /**
     * Actualizar un prompt existente.
     *
     * @param string $name
     * @param string $content
     * @param string|null $description
     * @return \Luinuxscl\Prompts\Models\Prompt|null
     */
    public function update(string $name, string $content, ?string $description = null): ?Prompt
    {
        $normalizedName = $this->normalizeName($name);
        $prompt = Prompt::findByName($normalizedName);
        
        if (!$prompt) {
            return null;
        }
        
        $prompt->content = $content;
        
        if ($description !== null) {
            $prompt->description = $description;
        }
        
        $prompt->save();
        
        return $prompt;
    }
}
