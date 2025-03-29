document.addEventListener('DOMContentLoaded', function() {
    const bubbles = document.querySelectorAll('.message-bubble');
    const textarea = document.getElementById('observations');
    
    bubbles.forEach(bubble => {
        bubble.addEventListener('click', function(e) {
            e.preventDefault();
            
            const textToAdd = this.getAttribute('data-text');
            const startPos = textarea.selectionStart;
            const endPos = textarea.selectionEnd;
            const currentValue = textarea.value;
            
            // Determinar si debemos agregar un espacio antes
            let prefix = '';
            if (currentValue.length > 0 && startPos > 0) {
                // Verificar si el carácter anterior no es un espacio
                if (currentValue.charAt(startPos - 1) !== ' ') {
                    prefix = ' ';
                }
            }
            
            // Insertar el texto (con espacio si es necesario)
            textarea.value = currentValue.substring(0, startPos) + 
                             prefix + 
                             textToAdd + 
                             currentValue.substring(endPos, currentValue.length);
            
            // Posicionar el cursor
            const newCursorPos = startPos + prefix.length + textToAdd.length;
            textarea.selectionStart = newCursorPos;
            textarea.selectionEnd = newCursorPos;
            
            textarea.focus();
            
            // Disparar evento de cambio
            const event = new Event('input', { bubbles: true });
            textarea.dispatchEvent(event);
            
            // Efecto visual
            this.classList.add('clicked');
            setTimeout(() => this.classList.remove('clicked'), 300);
        });
    });
});