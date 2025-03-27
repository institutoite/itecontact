<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Contacto</title>
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/contact/contact.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="contact-form-container">
        <!-- Header -->
        <header class="form-header">
            <h1><i class="fas fa-user-plus"></i> Nuevo Contacto</h1>
            <a href="{{ route('contacts.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </header>

        <!-- Mensaje de éxito (oculto inicialmente) -->
        <div id="success-message" class="success-alert hidden">
            <i class="fas fa-check-circle"></i>
            <span>Contacto guardado con éxito</span>
            
            <!-- Botón de WhatsApp (oculto inicialmente) -->
            <div id="whatsapp-action" class="hidden" style="margin-top: 15px;">
                <a id="whatsapp-link" href="#" target="_blank" class="whatsapp-btn">
                    <i class="fab fa-whatsapp"></i> Abrir WhatsApp
                </a>
            </div>
        </div>

        <!-- Formulario -->
        {{-- <form id="contact-form" class="contact-form" action="{{ route('contacts.store') }}" method="POST"> --}}
            <form id="contact-form" class="contact-form" action="{{ route('contacts.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name"><i class="fas fa-user"></i> Nombre del Contacto</label>
                <input type="text" id="name" name="name" value="David Eduardo flores Mamani" >
                <div class="error-message"></div>
            </div>
            
            <div class="form-group">
                <label for="phone"><i class="fas fa-phone"></i> Número de Teléfono</label>
                <div class="phone-input">
                    <span>+</span>
                    <input type="text" id="phone" name="phone" placeholder="Ej: 5491112345678" value="71039910">
                </div>
                <div class="error-message"></div>
                
            </div>
            <div class="form-group">
                <label for="observations"><i class="fas fa-edit"></i> Mensaje</label>
                <textarea id="observations" name="observations" rows="3">este es un comentaio</textarea>
            </div>


                     
            <div id="success-message" class="success-alert hidden">
                <i class="fas fa-check-circle"></i>
                <span>Contacto guardado con éxito</span>
                
                <div class="download-section">
                    <button id="descargar-contacto" class="download-btn">
                        <i class="fas fa-download"></i> Descargar Contacto (VCF)
                    </button>
                </div>

                <div class="download-section">
                    <button id="descargar-contacto" class="download-btn">
                        <i class="fas fa-download"></i> Descargar Contacto (VCF)
                    </button>
                </div>

                
                <div class="whatsapp-message hidden">
                    <i class="fab fa-whatsapp"></i>
                    <span>Redirigiendo a WhatsApp...</span>
                </div>
            </div>



    

            <!-- Botones -->
            <div class="form-actions">
                <button type="submit" id="boton-guardar" class="primary-btn">
                    <i class="fas fa-save"></i> Guardar Contacto
                </button>
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('js/contact/contact.js') }}"></script>
</body>
</html>