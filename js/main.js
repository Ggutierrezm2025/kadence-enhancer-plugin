$(document).ready(function() {
    let currentStep = 1;
    const totalSteps = $(".form-section").length;

    function updateProgressBar() {
        const progress = (currentStep / totalSteps) * 100;
        $(".progress-bar").css("width", progress + "%").text("Paso " + currentStep + " de " + totalSteps);
    }

    function showStep(step) {
        $(".form-section").removeClass("current");
        $("#step" + step).addClass("current");
        currentStep = step;
        updateProgressBar();
    }

    function validateStep(step) {
        let isValid = true;
        $("#step" + step + " [required]").each(function() {
            $(this).removeClass("error-border"); // Remover borde de error previo
            if ($(this).is(':visible')) { // Solo validar campos visibles
                if ($(this).is('select') && $(this).val() === "") {
                    isValid = false;
                    $(this).addClass("error-border");
                } else if ($(this).is('input[type="checkbox"]') && !$(this).is(':checked')) {
                    // Podríamos añadir validación específica para checkboxes si es 'required'
                    // y no está marcado, pero el atributo 'required' en checkbox
                    // ya es manejado por el navegador si el form se submite.
                    // Para validación custom por JS, se haría aquí.
                    // Por ahora, si un checkbox es 'required' y no está marcado,
                    // la validación fallará si se implementa abajo.
                } else if ($(this).val() === "") {
                    isValid = false;
                    $(this).addClass("error-border");
                }

                // Validación específica para correo
                if ($(this).attr("type") === "email" && $(this).val() !== "") {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test($(this).val())) {
                        isValid = false;
                        $(this).addClass("error-border");
                        // Podríamos agregar un mensaje de error específico
                        // $(this).after('<small class="text-danger">Correo inválido.</small>');
                    }
                }
            }
        });

        // Validación específica para la tabla de protegidos en el paso 4
        if (step === 4) {
            let protegidosValidos = true;
            $("#tablaProtegidos tbody tr").each(function() {
                $(this).find("input[type='text'], select").each(function() {
                     $(this).removeClass("error-border");
                    if ($(this).val() === "" && !$(this).hasClass('protegidoEdad')) { // Edad es readonly
                        protegidosValidos = false;
                        $(this).addClass("error-border");
                    }
                });
            });
            if (!protegidosValidos && $("#tablaProtegidos tbody tr").length > 0) { // Solo validar si hay protegidos
                isValid = false;
                // alert("Por favor, complete todos los campos de los protegidos.");
            }
             if (!$("#declaracionSalud").is(":checked")) {
                isValid = false;
                $("#declaracionSalud").addClass("error-border");
                // alert("Debe aceptar la declaración de salud.");
            } else {
                 $("#declaracionSalud").removeClass("error-border");
            }
        }


        // Limpiar errores de campos que ahora son válidos o no visibles
        $("#step" + step + " .form-control, #step" + step + " .form-check-input").each(function() {
            if (!$(this).hasClass("error-border") && $(this).data('had-error')) {
                 // Si ya no tiene error-border pero lo tuvo, quitarlo
                $(this).removeData('had-error');
            }
            if ($(this).hasClass("error-border") && !$(this).is(':visible')) {
                $(this).removeClass("error-border"); // Quitar error si el campo ya no es visible
            }
             if ($(this).hasClass("error-border") && $(this).val() !== "" && !($(this).is('select') && $(this).val() === "")) {
                // Si tiene valor y aun tiene el borde (ej. email que luego se corrigió sin revalidar)
                // Esto es un fallback, la revalidación al escribir sería mejor
                if ($(this).attr("type") === "email") {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailPattern.test($(this).val())) {
                         $(this).removeClass("error-border");
                    }
                } else if(!$(this).is('select')) { // no quitar si es select y el valor es ""
                    $(this).removeClass("error-border");
                }
            }
        });


        if (!isValid) {
            alert("Por favor, complete todos los campos obligatorios resaltados en rojo.");
        }
        return isValid;
    }

    $(".next-step").click(function() {
        if (validateStep(currentStep)) {
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        }
    });

    $(".prev-step").click(function() {
        // No se necesita validación al retroceder
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    // Remover borde de error al escribir o cambiar
    $("body").on("keyup change", ".error-border", function() {
        if ($(this).val() !== "" || ($(this).is('select') && $(this).val() !== "") || ($(this).is(':checkbox') && $(this).is(':checked'))) {
            // Check email format specifically if it's an email field
            if ($(this).attr("type") === "email") {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailPattern.test($(this).val())) {
                    $(this).removeClass("error-border");
                }
            } else {
                $(this).removeClass("error-border");
            }
        }
    });


    function getFormattedDate() {
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        const year = today.getFullYear();
        return day + '-' + month + '-' + year;
    }

    // Inicializar la barra de progreso y mostrar el primer paso
    updateProgressBar();
    $("#step1").addClass("current");

    // Establecer ciudad (placeholder) y fecha actual
    $("#ciudadAgente").text("Ciudad Ejemplo"); // Este valor vendrá de la BD
    $("#fechaActual").text(getFormattedDate());

    // Llenar selects de fecha de nacimiento
    function populateDateSelects() {
        const diaSelect = $("#diaNacimiento");
        const mesSelect = $("#mesNacimiento");
        const anoSelect = $("#anoNacimiento");

        // Días
        for (let i = 1; i <= 31; i++) {
            diaSelect.append(`<option value="${i}">${i}</option>`);
        }

        // Meses
        const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        meses.forEach((mes, index) => {
            mesSelect.append(`<option value="${index + 1}">${mes}</option>`);
        });

        // Años (desde hace 90 años hasta hace 18 años)
        const currentYear = new Date().getFullYear();
        const minYear = currentYear - 90;
        const maxYear = currentYear - 18;
        for (let i = maxYear; i >= minYear; i--) {
            anoSelect.append(`<option value="${i}">${i}</option>`);
        }
    }

    populateDateSelects();

    // Calcular edad
    function calculateAge() {
        const dia = $("#diaNacimiento").val();
        const mes = $("#mesNacimiento").val();
        const ano = $("#anoNacimiento").val();

        if (dia && mes && ano) {
            const birthDate = new Date(ano, mes - 1, dia);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            $("#edadTitular").val(age >= 0 ? age : '');
        } else {
            $("#edadTitular").val('');
        }
    }

    $("#diaNacimiento, #mesNacimiento, #anoNacimiento").change(calculateAge);

    // Lógica para el checkbox "No estoy trabajando actualmente"
    $("#noTrabajaCheck").change(function() {
        if ($(this).is(":checked")) {
            $("#datosLaborales").hide();
            // Opcional: Limpiar campos laborales si se marca el checkbox
            // $("#direccionTrabajo, #telefonoTrabajo").val('');
        } else {
            $("#datosLaborales").show();
        }
    });

    // Lógica para la tabla de protegidos
    let protegidoCount = 0;

    function actualizarContadorProtegidos() {
        $("#cantidadProtegidos").text(protegidoCount);
    }

    function calcularEdadProtegido(fechaNac) { // dd-mm-yyyy
        if (!fechaNac || !/^\d{2}-\d{2}-\d{4}$/.test(fechaNac)) return '';
        const parts = fechaNac.split('-');
        const birthDate = new Date(parts[2], parts[1] - 1, parts[0]);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age >= 0 ? age : '';
    }

    $("#agregarProtegido").click(function() {
        protegidoCount++;
        const newRow = `
            <tr id="protegidoRow${protegidoCount}">
                <td><input type="text" class="form-control form-control-sm" name="protegidoNombres[]" placeholder="Nombres"></td>
                <td><input type="text" class="form-control form-control-sm" name="protegidoApellidos[]" placeholder="Apellidos"></td>
                <td><input type="text" class="form-control form-control-sm" name="protegidoCedula[]" placeholder="Cédula"></td>
                <td><input type="text" class="form-control form-control-sm protegidoFechaNac" name="protegidoFechaNac[]" placeholder="dd-mm-yyyy"></td>
                <td><input type="text" class="form-control form-control-sm protegidoEdad" name="protegidoEdad[]" readonly></td>
                <td>
                    <select class="form-control form-control-sm" name="protegidoSexo[]">
                        <option value="">Sexo</option>
                        <option value="Masculino">M</option>
                        <option value="Femenino">F</option>
                    </select>
                </td>
                <td><input type="text" class="form-control form-control-sm" name="protegidoParentesco[]" placeholder="Parentesco"></td>
                <td><button type="button" class="btn btn-danger btn-sm eliminarProtegido" data-rowid="${protegidoCount}">Eliminar</button></td>
            </tr>
        `;
        $("#tablaProtegidos tbody").append(newRow);
        actualizarContadorProtegidos();
    });

    $("#tablaProtegidos").on("click", ".eliminarProtegido", function() {
        const rowId = $(this).data("rowid");
        $("#protegidoRow" + rowId).remove();
        // No decrementamos protegidoCount para evitar reutilizar IDs si se vuelve a agregar,
        // pero sí actualizamos el contador visual basado en las filas existentes.
        // Mejor, contamos las filas reales para el contador.
        protegidoCount = $("#tablaProtegidos tbody tr").length;
        actualizarContadorProtegidos();
    });

    $("#tablaProtegidos").on("change", ".protegidoFechaNac", function() {
        const fechaNac = $(this).val();
        const edad = calcularEdadProtegido(fechaNac);
        $(this).closest("tr").find(".protegidoEdad").val(edad);
    });

    actualizarContadorProtegidos(); // Inicializar contador

    // Lógica para formas de pago
    $("#formaPago").change(function() {
        const seleccion = $(this).val();
        $("#detallesNomina").hide().find("[required]").prop("required", false);
        $("#detallesDomiciliacion").hide().find("[required_temp]").prop("required", false); // Usamos required_temp para no interferir con el general
        $("#listaBancos").hide();


        if (seleccion === "nomina") {
            $("#detallesNomina").show().find("input, select").prop("required", true);
        } else if (seleccion === "domiciliacion") {
            $("#detallesDomiciliacion").show().find("input, select").prop("required", true);
        } else if (seleccion === "bancos") {
            $("#listaBancos").show();
            // No hay campos 'required' dentro de listaBancos por ahora
        }
        // Clear previous errors from hidden sections when payment method changes
        $("#detallesNomina, #detallesDomiciliacion").find(".error-border").removeClass("error-border");
    });


    // Ajuste en la validación de campos laborales
    $("#noTrabajaCheck").change(function() {
        if ($(this).is(":checked")) {
            $("#datosLaborales").hide();
            $("#datosLaborales").find("textarea, input").prop("required", false).removeClass("error-border");
        } else {
            $("#datosLaborales").show();
            $("#datosLaborales").find("textarea, input").prop("required", true);
        }
    });

    // Ensure laboral fields are required by default if checkbox is not checked
    if (!$("#noTrabajaCheck").is(":checked")) {
        $("#datosLaborales").find("textarea, input").prop("required", true);
    } else {
        $("#datosLaborales").find("textarea, input").prop("required", false);
    }


    // Validacion del formulario al enviar
    $("#multiStepForm").submit(function(event) {
        if (!validateStep(currentStep)) { // Validar el último paso antes de enviar
            event.preventDefault(); // Detener el envío si hay errores
            alert("Por favor, corrija los errores en el formulario antes de enviar.");
        } else {
            // Aquí se podría agregar una confirmación antes de enviar
            // o directamente permitir el envío si todo es válido.
            alert("Formulario enviado (simulación)."); // Placeholder
            event.preventDefault(); // Prevenir envío real para este ejemplo
            // window.location.reload(); // Opcional: recargar para limpiar
        }
    });
});
