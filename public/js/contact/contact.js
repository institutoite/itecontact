$(document).ready(function() {
    const form = $('#contact-form');
    const successMessage = $('#success-message');
    const defaultMessage = "Hola {name}, este es mi numero te contacto desde *ite contact*";

    $('#boton-guardar').click(function(e) {
        e.preventDefault();
        
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
                            sendWhatsAppMessage(response.vcf_url,response.contact.phone, response.contact.name);
                        } catch (whatsappError) {
                            console.error('Error al abrir WhatsApp:', whatsappError);
                            showWarning('El contacto se guardó pero hubo un problema al abrir WhatsApp');
                        }
                        
                        setTimeout(resetForm, 5000);
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

    function sendWhatsAppMessage(url,phone, name) {
        const personalizedMessage = defaultMessage.replace('{name}', name);
        const whatsappUrl = `https://wa.me/591${phone}?text=${encodeURIComponent(personalizedMessage)} ${url}`;
        
        setTimeout(() => {
            window.open(whatsappUrl, '_blank');
        }, 500);
    }
    // function sendWhatsAppMessage(phone, name) {
    //     const personalizedMessage = defaultMessage.replace('{name}', name);
    //     const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(personalizedMessage)}`;
    //     window.open(whatsappUrl, '_blank');
    // }

    function resetForm() {
        form[0].reset();
        successMessage.addClass('hidden');
        form.removeClass('hidden');
    }

    function clearErrors() {
        $('.error-message').text('');
        $('.error-alert').remove();
    }

    // function showSuccessUI(response) {
    //     if (!response.contact || !response.vcf_url) {
    //         throw new Error('Datos incompletos en la respuesta');
    //     }
    
    //     const successHTML = `
    //         <div class="success-content">
    //             <i class="fas fa-check-circle success-icon"></i>
    //             <h3>¡Contacto guardado con éxito!</h3>
    //             <p>Nombre: ${response.contact.name}</p>
    //             <p>Teléfono: ${response.contact.phone}</p>
                
    //             <div class="success-actions">
    //                 <a href="${response.vcf_url}" class="btn vcf-download-btn" download>
    //                     <i class="fas fa-download"></i> Descargar VCF
    //                 </a>
    //                 <a href="https://wa.me/${response.contact.phone}" target="_blank" class="btn whatsapp-btn">
    //                     <i class="fab fa-whatsapp"></i> Abrir WhatsApp
    //                 </a>
    //             </div>
    //         </div>
    //     `;
        
    //     successMessage.html(successHTML).removeClass('hidden');
    //     form.addClass('hidden');
    // }
    
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
                $(`#${field}`).next('.error-message').text(messages.join(', '));
            }
        }
    }

    function showSuccessUI(response) {
    
        // Crear el contenido de éxito
        const successHTML = `
            <i class="fas fa-check-circle"></i>
            <span>Contacto guardado con éxito</span>
            
            <div class="download-section">
                <button id="descargar-contacto" class="download-btn">
                    <i class="fas fa-download"></i> Descargar Contacto (VCF)
                </button>
            </div>
            
            <div class="whatsapp-message hidden">
                <i class="fab fa-whatsapp"></i>
                <span>Redirigiendo a WhatsApp...</span>
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
        
        // Ocultar el formulario
        form.addClass('hidden');
        
        // Mostrar mensaje de WhatsApp después de 3 segundos
        setTimeout(function() {
            $('.whatsapp-message').removeClass('hidden');
            
            // Redirigir a WhatsApp después de 2 segundos más
            setTimeout(function() {
                sendWhatsAppMessage(response.contact.phone, response.contact.name);
            }, 1000);
        }, 10000);
    }
    
    
});