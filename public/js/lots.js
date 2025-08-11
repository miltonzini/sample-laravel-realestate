// console.log('lots called (public)');

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('lotFormModal');
    const openModalBtn = document.getElementById('lotDetailsFormModalAnchor');
    const closeSpan = document.querySelector('.closeLotDetailsFormModalSpan');
    
    if (!modal || !openModalBtn || !closeSpan) {
        console.error('No se encontraron los elementos necesarios para el modal');
        return;
    }

    openModalBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });

    closeSpan.addEventListener('click', function() {
        closeModal();
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    function closeModal() {
        modal.style.display = 'none';
        
        const form = document.getElementById('lot-details-form');
        if (form) {
            form.reset();
        }
    }

    modal.querySelector('.modal-content').addEventListener('click', function(event) {
        event.stopPropagation();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    });
});



async function contactFromLotDetails(action, method, data, submitButton) {
    console.log('Enviando consulta sobre el lote/terreno...');
    
    submitButton.disabled = true;
    const originalValue = submitButton.value;
    submitButton.value = 'Enviando mensaje...';
  
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const response = await fetch(action, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: data
        });
  
        const responseData = await response.json();
        
        if (response.ok && responseData.success) {
            toastr.success(responseData.message || 'Mensaje enviado exitosamente');
            document.querySelector('#lot-details-form').reset();
            
            const modal = document.querySelector('#lotFormModal');
            if (modal) modal.style.display = 'none';
        } else {
            const errors = responseData.errors || { error: responseData.message || 'Error desconocido' };
            Object.values(errors).forEach(error => toastr.error(error));
        }
    } catch (error) {
        console.error("Error de red:", error);
        toastr.error("Error al enviar el mensaje. Por favor, intente nuevamente.");
    } finally {
        submitButton.disabled = false;
        submitButton.value = originalValue;
    }
  }
  
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#lot-details-form');
    
    if (form) {
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            console.log('Formulario enviado');
            
            const submitButton = this.querySelector('#lot-details-contact-button');
            
            if (!submitButton) {
                console.error('Botón de envío no encontrado');
                return;
            }
  
            const action = this.getAttribute('action');
            const method = this.getAttribute('method');
            const formData = new FormData(this);
  
            console.log('Datos del formulario:', Object.fromEntries(formData.entries()));
            
            if (typeof showSendingMessageToast === 'function') {
                showSendingMessageToast();
            }
            
            await contactFromLotDetails(action, method, formData, submitButton);
        });
    } else {
        console.warn('Formulario no encontrado en el DOM');
    }
  });