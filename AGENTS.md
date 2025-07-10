# Instrucciones para Agentes de IA (Jules)

Al trabajar en este proyecto de formulario multi-paso, ten en cuenta lo siguiente:

## Estructura del Proyecto

*   **`index.html`**: Contiene todo el markup HTML del formulario. Las secciones del formulario están divididas por `divs` con IDs `step1` a `step5`.
*   **`js/main.js`**: Aquí reside toda la lógica de JavaScript y jQuery. Incluye:
    *   Navegación entre pasos (`showStep`, `updateProgressBar`).
    *   Validación de campos por paso (`validateStep`).
    *   Poblado dinámico de selects (ej. fecha de nacimiento).
    *   Cálculo de edad.
    *   Manejo de la tabla de protegidos (agregar/eliminar filas, contador).
    *   Lógica para mostrar/ocultar campos condicionales (datos laborales, formas de pago).
*   **`funciones.php`**: Es un *placeholder* para la lógica del backend. Actualmente no se ejecuta, pero está destinado a manejar interacciones con la base de datos, validaciones del lado del servidor y el procesamiento final del formulario. No intentes implementar lógica PHP compleja aquí sin una base de datos o un entorno de servidor configurado.
*   **Bootstrap y jQuery**: Se cargan desde CDNs. No es necesario instalarlos localmente para que el frontend funcione.

## Consideraciones de Desarrollo

1.  **Validación**:
    *   La validación del lado del cliente se basa en el atributo `required` en los elementos del formulario y lógica adicional en `validateStep` en `main.js`.
    *   Los campos con errores se marcan con la clase `error-border`.
    *   Al realizar cambios en los campos o su visibilidad condicional, asegúrate de que la lógica de validación (incluyendo el ajuste dinámico de `required` y la limpieza de `error-border`) siga funcionando correctamente.
    *   La validación de la tabla de protegidos y la declaración de salud en el paso 4 tienen lógica específica en `validateStep`.

2.  **Campos Dinámicos y Condicionales**:
    *   **Datos Laborales (Paso 3)**: Los campos `direccionTrabajo` y `telefonoTrabajo` son `required` solo si el checkbox `noTrabajaCheck` NO está marcado. Su visibilidad y estado `required` se gestionan en `main.js`.
    *   **Formas de Pago (Paso 5)**: Los campos dentro de `detallesNomina` y `detallesDomiciliacion` son `required` solo si la sección correspondiente está visible (basado en la selección de `formaPago`). Su visibilidad y estado `required` también se gestionan en `main.js`.
    *   **Tabla de Protegidos (Paso 4)**: Los campos dentro de cada fila de protegido son `required`. La edad se calcula automáticamente y es `readonly`.

3.  **IDs y Clases Importantes**:
    *   Secciones: `step1`, `step2`, ..., `step5`.
    *   Botones de navegación: `.next-step`, `.prev-step`.
    *   Barra de progreso: `.progress-bar`.
    *   Clase de error: `error-border`.
    *   Revisa `index.html` y `main.js` para otros IDs y clases relevantes si necesitas modificar elementos específicos.

4.  **Backend (PHP)**:
    *   Cualquier tarea que implique persistencia de datos (guardar en BD), validación del lado del servidor, o carga de datos desde una fuente externa (ej. lista de bancos desde BD) debe ser conceptualizada para `funciones.php`.
    *   Por ahora, si se te pide implementar algo que claramente requiere backend (ej. "guardar el formulario en la base de datos"), debes indicar que esto iría en `funciones.php` y que la infraestructura de backend (servidor, base de datos) no está configurada en el entorno actual. Puedes, sin embargo, preparar el formulario HTML para que envíe los datos (ej. usando el método POST y nombres de campo adecuados).

5.  **Pruebas**:
    *   Después de cualquier cambio, prueba exhaustivamente la navegación entre todos los pasos.
    *   Verifica que la validación funcione como se espera en cada paso, incluyendo los campos condicionales.
    *   Prueba la funcionalidad de agregar/eliminar protegidos.
    *   Asegúrate de que los cálculos de edad sean correctos.
    *   Prueba en diferentes tamaños de pantalla (Bootstrap debería manejar la responsividad, pero verifica que no haya elementos rotos).

## Ejemplo de Tarea Común y Cómo Abordarla

*   **"Añadir un nuevo campo X al Paso Y, que sea obligatorio."**
    1.  Añade el HTML para el campo en `index.html` dentro del `div` del Paso Y. No olvides el atributo `required`.
    2.  Si el campo tiene lógica especial (ej. condicional, o afecta a otros campos), actualiza `main.js`.
    3.  Verifica que la validación en `validateStep` lo recoja correctamente. Si es un nuevo tipo de campo o tiene reglas de validación únicas no cubiertas, podrías necesitar ajustar `validateStep`.
    4.  Prueba el formulario.

Siguiendo estas pautas, deberías poder trabajar eficientemente en este proyecto.
