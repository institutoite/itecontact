<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>itecontact</title>

    <link rel="icon" href="{{ asset('image/ite.ico') }}" type="image/x-icon">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/contact/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact/globos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact/header.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="contact-form-container">
        <!-- Header -->
        <header class="form-header">
            <div class="logo-wrapper">
                <div class="logo-container">
                    <div class="logo-icon">
                        <img src="{{ asset('image/logo.png') }}" alt="Logo itecontac" class="logo-image">
                    </div>
                    <h1>itecontact</h1>
                </div>
                <span class="slogan">¡Fue fácil agregarte!</span>
            </div>
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
                <input type="text" id="name" name="name" placeholder="" value="" >
                <div class="error-message"></div>
            </div>
            
            <div class="form-group">
                <label for="phone"><i class="fas fa-phone"></i> Número de Teléfono</label>
                <div class="phone-input">
                    
                    <input type="number" id="phone" name="phone" placeholder="Ej: 60902299" value="">
                </div>
                <div class="error-message"></div>
                
            </div>
            <div class="form-group">
                <label for="observations"><i class="fas fa-edit"></i> Mensaje</label>
                <div class="message-buttons">
                    <button class="message-bubble" data-text=".">.</button>
                    <button class="message-bubble" data-text="Hola">Hola</button>
                    <button class="message-bubble" data-text="Este es mi número soy: ">Presentación</button>
                    <button class="message-bubble" data-text="¿Cómo estás?">¿Cómo estás?</button>
                    <button class="message-bubble" data-text="Te contacto por: ">Motivo</button>
                </div>
                <textarea id="observations" name="observations" rows="2"></textarea>
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
    <script src="{{ asset('js/contact/globos.js') }}"></script>
</body>
</html>