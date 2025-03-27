<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Contacto</title>
    <link rel="stylesheet" href="{{ asset('css/contact/show.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="contact-details-container">
        <!-- Header Section -->
        <header class="contact-header">
            <div class="header-content">
                <h1 class="header-title">
                    <i class="fas fa-user-circle"></i> Detalles del Contacto
                </h1>
                <a href="{{ route('contacts.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="contact-content">
            <!-- Success Message -->
            <div id="success-message" class="alert-message success-message @if(!session('success')) hidden @endif">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>

            <!-- Contact Card -->
            <div class="contact-card">
                <!-- Contact Header -->
                <div class="contact-card-header">
                    <div class="contact-title">
                        <h2>{{ $contact->name }}</h2>
                        <span class="contact-status {{ $contact->status == 'Registrado' ? 'registered' : 'not-registered' }}">
                            {{ $contact->status }}
                        </span>
                    </div>
                    <div class="contact-actions">
                        <a href="{{ route('contacts.edit', $contact->id) }}" class="action-btn edit-btn">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <button id="delete-btn" class="action-btn delete-btn">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </div>
                </div>

                <!-- Contact Details -->
                <div class="contact-details">
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-phone"></i> Teléfono:</span>
                        <span class="detail-value">{{ $contact->phone }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-info-circle"></i> Observaciones:</span>
                        <span class="detail-value">{{ $contact->observations ?: 'Sin observaciones' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-user"></i> Creado por:</span>
                        <span class="detail-value">{{ $contact->creator }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-calendar-alt"></i> Fecha creación:</span>
                        <span class="detail-value">{{ $contact->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <!-- WhatsApp Integration -->
                <div class="whatsapp-section">
                    <h3><i class="fab fa-whatsapp"></i> Acciones con WhatsApp</h3>
                    <div class="whatsapp-actions">
                        <a href="{{ $whatsappUrl }}" target="_blank" class="whatsapp-btn">
                            <i class="fab fa-whatsapp"></i> Abrir Chat
                        </a>
                        <button id="send-message-btn" class="whatsapp-btn secondary">
                            <i class="fas fa-paper-plane"></i> Enviar Mensaje
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="modal-overlay hidden">
            <div class="modal-container">
                <div class="modal-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h2>Confirmar eliminación</h2>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este contacto? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button id="cancel-delete" class="secondary-btn">Cancelar</button>
                    <form id="delete-form" action="{{ route('contacts.destroy', $contact->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="primary-btn danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Message Modal -->
        <div id="message-modal" class="modal-overlay hidden">
            <div class="modal-container">
                <div class="modal-header">
                    <i class="fas fa-paper-plane"></i>
                    <h2>Enviar mensaje por WhatsApp</h2>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message-content">Mensaje:</label>
                        <textarea id="message-content" class="form-input" rows="4" placeholder="Escribe tu mensaje aquí..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="cancel-message" class="secondary-btn">Cancelar</button>
                    <a id="send-whatsapp" href="{{ $whatsappUrl }}" target="_blank" class="primary-btn">
                        <i class="fab fa-whatsapp"></i> Enviar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/contact/show.js') }}"></script>
</body>
</html>