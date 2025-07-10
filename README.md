# Formulario Multi-Step de Afiliación

Este proyecto implementa un formulario multi-paso para la solicitud de afiliación a un servicio de previsión funeraria. Está construido utilizando HTML, Bootstrap 4 para el diseño responsivo y la estructura, y jQuery para la interactividad, navegación entre pasos y validación de campos del lado del cliente.

## Características Implementadas

*   **Formulario Multi-Paso:** El formulario se divide en 5 secciones o pasos para facilitar el llenado.
*   **Barra de Progreso:** Indica visualmente en qué paso del formulario se encuentra el usuario.
*   **Navegación:** Botones "Siguiente" y "Anterior" para moverse entre las secciones del formulario.
*   **Validación de Campos (jQuery):**
    *   Los campos marcados como `required` en el HTML son validados antes de pasar al siguiente paso.
    *   Los campos con errores se resaltan con un borde rojo.
    *   Se muestra una alerta general si hay campos inválidos.
    *   Validación específica para el formato de correo electrónico.
    *   Los campos dentro de secciones condicionales (datos laborales, detalles de pago) ajustan dinámicamente su estado `required` y se validan solo si son visibles.
*   **Campos Dinámicos:**
    *   **Fecha Actual:** Se muestra automáticamente en el primer paso.
    *   **Cálculo de Edad:** La edad del titular y de los protegidos se calcula automáticamente a partir de la fecha de nacimiento.
    *   **Tabla de Protegidos:** Permite agregar y eliminar dinámicamente filas para los beneficiarios.
    *   **Contador de Protegidos:** Se actualiza en tiempo real.
    *   **Campos Condicionales:**
        *   La sección de datos laborales puede omitirse.
        *   Los campos para detalles de pago (nómina, domiciliación) aparecen según la forma de pago seleccionada.
*   **Estructura de Archivos:**
    *   `index.html`: Contiene la estructura HTML del formulario.
    *   `js/main.js`: Contiene toda la lógica de JavaScript y jQuery.
    *   `funciones.php`: Archivo placeholder para la futura lógica de backend (conexión a BD, validaciones server-side, etc.).
    *   Se utilizan CDNs para Bootstrap y jQuery.

## Estructura del Formulario

1.  **Paso 1: Información del Contrato**
    *   Número de contrato, código de empresa, tipo de plan.
    *   Ciudad del agente y fecha actual.
2.  **Paso 2: Sección A: Identificación del titular**
    *   Datos personales del cliente: nombres, cédula, sexo, fecha de nacimiento, edad, dirección, teléfonos, correo.
3.  **Paso 3: Sección B: Identificación Laboral**
    *   Datos laborales del cliente, con opción a omitir si no trabaja.
4.  **Paso 4: Sección C: Identificación de los protegidos**
    *   Tabla dinámica para agregar beneficiarios (nombres, cédula, fecha de nacimiento, edad, sexo, parentesco).
    *   Declaración de salud.
5.  **Paso 5: Sección D: Condiciones e Información del servicio**
    *   Tabla de condiciones de cuotas.
    *   Selección de forma de pago con campos condicionales para nómina y domiciliación.

## Próximos Pasos (Backend y Mejoras)

*   Integrar `funciones.php` para la lógica del servidor.
*   Conectar a una base de datos para:
    *   Poblar dinámicamente los `select` (estados, ciudades, bancos, etc.).
    *   Almacenar los datos del formulario.
    *   Gestionar perfiles de agentes (para la ciudad automática).
*   Implementar validaciones robustas del lado del servidor.
*   Integrar APIs externas para validación de cédula, RIF, tarjetas de crédito, etc.
*   Mejorar la presentación de mensajes de error (más específicos por campo).
*   Añadir internacionalización si es necesario.
*   Escribir pruebas unitarias y de integración.

## Cómo Ejecutar

1.  Descargar los archivos (`index.html`, `js/main.js`, `funciones.php`).
2.  Asegurarse de que la carpeta `js` esté en el mismo directorio que `index.html`.
3.  Abrir `index.html` en un navegador web moderno.

No se requiere un servidor web para la funcionalidad actual del frontend, pero será necesario para la integración con `funciones.php` y la base de datos.
