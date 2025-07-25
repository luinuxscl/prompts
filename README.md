# Laravel Prompts

Un paquete Laravel minimalista para gestionar y anidar prompts de texto con soporte para variables.

## Características

- **Gestión de prompts**: Sistema sencillo para almacenar y recuperar plantillas de texto
- **Anidamiento de prompts**: Capacidad para incluir prompts dentro de otros prompts
- **Variables personalizadas**: Soporte para variables en formato `::variable::`
- **Normalización de nombres**: Los nombres de prompts se normalizan automáticamente para evitar duplicados
- **Prompts de sistema**: Soporte para prompts que no se pueden eliminar accidentalmente
- **Relaciones avanzadas entre prompts**: Un prompt puede tener múltiples padres y/o hijos, permitiendo estructuras complejas y reutilizables.
- **Sin interfaz gráfica**: Diseñado para uso programático a través de un API simple
- **Ligero y eficiente**: Implementación minimalista sin dependencias innecesarias

## Instalación

```bash
composer require luinuxscl/prompts
```

Publica las migraciones:

```bash
php artisan vendor:publish --provider="Luinuxscl\Prompts\PromptsServiceProvider" --tag="migrations"
```

Ejecuta las migraciones:

```bash
php artisan migrate
```

Opcionalmente, publica el archivo de configuración:

```bash
php artisan vendor:publish --provider="Luinuxscl\Prompts\PromptsServiceProvider" --tag="config"
```

## Uso básico

### Relaciones avanzadas entre prompts (padres e hijos)

Puedes asociar cualquier prompt a varios padres y/o hijos para crear estructuras complejas y reutilizables. Esto se logra mediante relaciones muchos-a-muchos autoreferenciales.

#### Ejemplo básico de asociación:

```php
use Luinuxscl\Prompts\Models\Prompt;

// Crear prompts
$promptA = Prompt::create(['name' => 'A', 'content' => 'Prompt A']);
$promptB = Prompt::create(['name' => 'B', 'content' => 'Prompt B']);
$promptC = Prompt::create(['name' => 'C', 'content' => 'Prompt C']);

// Asociar B y C como hijos de A
$promptA->children()->attach([$promptB->id, $promptC->id]);

// Asociar B como hijo de C también (B tiene dos padres: A y C)
$promptC->children()->attach($promptB->id);

// Consultar padres de B
$parentsOfB = $promptB->parents; // Collection de prompts

// Consultar hijos de A
$childrenOfA = $promptA->children; // Collection de prompts
```

- Puedes usar `attach`, `detach`, `sync` y demás métodos estándar de relaciones many-to-many de Eloquent.
- Las relaciones se gestionan automáticamente mediante la tabla pivote `prompt_relations`.
- Puedes anidar y reutilizar prompts en cualquier estructura que requieras.

### Crear un prompt

```php
use Luinuxscl\Prompts\Facades\Prompts;

// Crear un prompt básico
Prompts::create('saludo', 'Hola, bienvenido a nuestra plataforma.');

// Crear un prompt con descripción
Prompts::create('despedida', 'Gracias por visitarnos.', 'Mensaje de despedida');

// Crear un prompt de sistema (no se puede eliminar)
Prompts::createSystem('terminos_servicio', 'Estos son los términos de servicio...');

// También puedes marcar un prompt como sistema al crearlo
Prompts::create('politica_privacidad', 'Nuestra política de privacidad...', null, true);
```

### Anidar prompts

```php
// Crear prompts que se pueden anidar
Prompts::create('firma', 'Atentamente, El Equipo');
Prompts::create('email_completo', 'Hola cliente,\n\nGracias por tu mensaje.\n\n{{firma}}');

// Renderizar un prompt con anidamiento
$mensaje = Prompts::render('email_completo');
// Resultado: "Hola cliente,\n\nGracias por tu mensaje.\n\nAtentamente, El Equipo"
```

### Uso de variables

```php
// Crear un prompt con variables
Prompts::create('saludo_personal', 'Hola, ::nombre::! Bienvenido a ::plataforma::.');

// Renderizar el prompt con variables
$mensaje = Prompts::render('saludo_personal', [
    'nombre' => 'Juan',
    'plataforma' => 'Mi Aplicación'
]);
// Resultado: "Hola, Juan! Bienvenido a Mi Aplicación."
```

### Combinación de variables y anidamiento

```php
// Crear prompts con anidamiento y variables
Prompts::create('encabezado', 'AVISO IMPORTANTE DE ::empresa::');
Prompts::create('contenido', '{{encabezado}}\n\nEstimado/a ::cliente::,\n\nLe informamos que su cuenta ha sido activada correctamente.');

// Renderizar combinando ambas funcionalidades
$mensaje = Prompts::render('contenido', [
    'empresa' => 'MiEmpresa S.A.',
    'cliente' => 'Sr. Pérez'
]);
/* Resultado:
   "AVISO IMPORTANTE DE MiEmpresa S.A.
   
   Estimado/a Sr. Pérez,
   
   Le informamos que su cuenta ha sido activada correctamente."
*/
```

### Normalización de nombres

Los nombres de prompts se normalizan automáticamente: se convierten a minúsculas, se reemplazan espacios por guiones bajos y se recortan espacios al inicio y final. Esto hace que las siguientes llamadas sean equivalentes:

```php
Prompts::render('Mi Prompt');       // Se busca como "mi_prompt"
Prompts::render('mi_prompt');        // Coincide exactamente
Prompts::render('  MI PROMPT  ');    // También se normaliza a "mi_prompt"
```

## Licencia

MIT
