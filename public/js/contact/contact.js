$(document).ready(function() {
    const form = $('#contact-form');
    const successMessage = $('#success-message');
    const defaultMessage = "Hola {name}, este es mi numero te contacto desde *ite contact*";

    $('#boton-guardar').click(function(e) {
        e.preventDefault();
        clearErrors();
        const formData = new FormData(form[0]);
        const submitBtn = $(this);
        
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (!response || typeof response !== 'object') {
                    showError({ 
                        message: 'Respuesta inválida del servidor',
                        errors: { server: ['El servidor devolvió una respuesta no válida'] }
                    });
                    return;
                }
            
                if (response.success) {
                    try {
                        if (!response.contact || !response.vcf_url) {
                            throw new Error('La respuesta del servidor no contiene los datos esperados');
                        }
                        
                        showSuccessUI(response);
                        
                        try {
                            sendWhatsAppMessage(response.vcf_url, response.contact.phone, response.contact.name);
                        } catch (whatsappError) {
                            console.error('Error al abrir WhatsApp:', whatsappError);
                            showWarning('El contacto se guardó pero hubo un problema al abrir WhatsApp');
                        }
                        
                        // Eliminamos el setTimeout que reseteba el formulario
                    } catch (error) {
                        console.error('Error procesando respuesta:', error);
                        showError({
                            message: 'Error procesando la respuesta del servidor',
                            errors: { processing: [error.message] }
                        });
                    }
                } else {
                    showError({
                        message: response.message || 'Error al guardar el contacto',
                        errors: response.errors || {}
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error al enviar el formulario';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMessage += `: ${xhr.statusText}`;
                }
                showError({message: errorMessage});
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                submitBtn.html('<i class="fas fa-save"></i> Guardar Contacto');
            }
        });
    });


  /**
 * Envía un mensaje por WhatsApp para compartir un contacto
 * @param {string} vcfUrl - Enlace de descarga del contacto (VCF)
 * @param {string} phone - Número de teléfono del destinatario
 * @param {string} contactName - Nombre del contacto a compartir
 */
/**
 * Envía un mensaje por WhatsApp con el contacto y enlace a ITE Contact
 * @param {string} vcfUrl - Enlace de descarga del contacto (VCF)
 * @param {string} phone - Número de teléfono del destinatario
 * @param {string} contactName - Nombre del contacto a compartir
 */
function sendWhatsAppMessage(vcfUrl, phone, contactName) {
    // Obtener el mensaje del usuario o usar uno por defecto
    const userMessage = document.getElementById('observations').value.trim() || 
                      `Te comparto mis datos de contacto: ${contactName}`;
    
    // Construir el mensaje completo con formato WhatsApp
    const fullMessage = [
        `*${userMessage}*`,  // Mensaje del usuario en negrita
        '',
        '📋 *Detalles del contacto:*',
        `👤 ${contactName}`,
        `⬇️ Descargar contacto: ${vcfUrl}`,
        '',
        '_Enviado por itecontac_',
        'https://itecontact.ite.com.bo'
    ].join('\n');

    // Codificar URL para WhatsApp
    const whatsappUrl = `https://wa.me/591${phone}?text=${encodeURIComponent(fullMessage)}`;
    
    // Abrir en nueva pestaña con manejo de errores
    setTimeout(() => {
        try {
            const newWindow = window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
            if (!newWindow || newWindow.closed) {
                alert('Por favor permite ventanas emergentes para compartir el contacto');
            }
        } catch (error) {
            console.error('Error al abrir WhatsApp:', error);
            alert('Ocurrió un error al intentar compartir el contacto');
        }
    }, 500);
}
   
    function resetForm() {
        form[0].reset();
        successMessage.addClass('hidden');
        form.removeClass('hidden');
    }

    function clearErrors() {
        $('.error-message').text('').removeClass('error-message'); // Limpiar y remover clase
        $('.error-alert').remove(); // Eliminar alertas de error
        $('.is-invalid').removeClass('is-invalid'); // Remover clase de invalid
    }

    function showWarning(message) {
        const warningElement = $('<div class="alert alert-warning"></div>').html(`
            <i class="fas fa-exclamation-triangle"></i>
            <span>${message}</span>
        `);
        successMessage.prepend(warningElement);
    }
    
    function showError(error) {
        clearErrors();
        
        const errorContainer = $('<div class="error-alert"></div>').html(`
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text">${error.message}</span>
        `);
        
        form.before(errorContainer);
        
        if (error.errors) {
            for (const [field, messages] of Object.entries(error.errors)) {
                const $field = $(`#${field}`);
                $field.addClass('is-invalid');
                $field.after(`<div class="error-message text-danger">${messages.join(', ')}</div>`);
            }
        }
    }
    function showSuccessUI(response) {
        // Crear el contenido de éxito con botón para nuevo usuario
        const successHTML = `
            <div class="success-content">
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>Contacto guardado con éxito</span>
                </div>
                
                <div class="download-section">
                    <button id="descargar-contacto" class="download-btn">
                        <i class="fas fa-download"></i> Descargar Contacto (VCF)
                    </button>
                </div>
                
              
                
                <div class="new-contact-section">
                    <button id="nuevo-contacto" class="new-contact-btn">
                        <i class="fas fa-user-plus"></i> Crear nuevo contacto
                    </button>
                </div>

                <div class="whatsapp-message hidden">
                    <i class="fab fa-whatsapp"></i>
                    <span>Redirigido a WhatsApp...</span>
                </div>
            </div>
        `;
        
        // Actualizar el contenedor de éxito
        successMessage.html(successHTML).removeClass('hidden');
        
        if (!response.contact || !response.vcf_url) {
            throw new Error('Datos incompletos en la respuesta');
        }
    
        // Configurar el botón de descarga
        $('#descargar-contacto').on('click', function() {
            window.location.href = response.vcf_url;
        });
        
        // Configurar el botón para nuevo contacto
        $('#nuevo-contacto').on('click', function() {
            resetForm();
        });
        
        // Ocultar el formulario
        form.addClass('hidden');
        
        // Mostrar mensaje de WhatsApp después de 3 segundos
        setTimeout(function() {
            $('.whatsapp-message').removeClass('hidden');
            
            // Redirigir a WhatsApp después de 2 segundos más
            setTimeout(function() {
                sendWhatsAppMessage(response.vcf_url, response.contact.phone, response.contact.name);
            }, 1000);
        }, 10000);
    }
});