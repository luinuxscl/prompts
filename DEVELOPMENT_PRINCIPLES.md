# Principios de Desarrollo para el Paquete Prompts

Este documento establece las reglas y principios fundamentales que guiarán el desarrollo del paquete Prompts.

## Principios fundamentales

1. **Simplicidad como prioridad**: 
   - El código más sencillo es el mejor
   - Implementar solo lo estrictamente necesario
   - Evitar características innecesarias

2. **Código limpio y mantenible**:
   - Seguir principios SOLID
   - Utilizar patrones de diseño claros
   - Priorizar legibilidad sobre "inteligencia" del código

3. **Estándares PSR**:
   - Adherirse a PSR-4 para autoloading
   - Seguir PSR-12 para estilo de código
   - Mantener consistencia en todo el código base

4. **Documentación concisa**:
   - Comentarios claros pero no excesivos
   - Documentar solo lo necesario para entender el código
   - Los nombres de métodos y variables deben ser autodescriptivos

## Estructura y arquitectura

1. **Un solo modelo**:
   - Implementar únicamente el modelo Prompt con lo esencial
   - Mantener la tabla de base de datos simple

2. **Anidamiento eficiente**:
   - Optimizar el algoritmo de resolución de anidamiento
   - Implementar mecanismos para evitar bucles infinitos
   - Definir un límite claro de profundidad de anidamiento

3. **API mínima**:
   - Exponer solo los métodos estrictamente necesarios
   - Crear una fachada simple pero completa
   - Hacer la API intuitiva y fácil de usar

4. **Sin dependencias externas**:
   - Utilizar solo lo que proporciona Laravel
   - Minimizar el uso de paquetes de terceros

## Detalles de implementación

1. **Nombres descriptivos**:
   - Usar nomenclatura clara y consistente
   - Evitar abreviaturas confusas
   - Mantener coherencia en todo el código

2. **Evitar complejidad oculta**:
   - No "sobre-ingenierizar" el código
   - Preferir soluciones directas a soluciones elegantes pero complicadas
   - Un enfoque simple y directo es más valioso que la sofisticación

3. **Control de recursión**:
   - Implementar límites de anidamiento para evitar bucles infinitos
   - Manejar casos límite y bordes adecuadamente
   - Detectar y prevenir anidamientos circulares

4. **Errores explícitos**:
   - Manejo de errores claro y con mensajes útiles
   - Proporcionar información de depuración relevante
   - Fallar temprano y explícitamente cuando sea necesario
