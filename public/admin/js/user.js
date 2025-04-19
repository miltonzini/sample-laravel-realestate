console.log('user.js called (dashboard)');

async function createUserFromDashboard(action, method, data) {

    document.querySelector('#create-user-dashboard-button').disabled = true;
    document.querySelector('#create-user-dashboard-button').innerText = 'Creando Usuario...';

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const response = await fetch(action, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: data
        });

        document.querySelector('#create-user-dashboard-button').disabled = false;
        document.querySelector('#create-user-dashboard-button').innerText = 'Crear Usuario';

        if (response.ok) {
            const responseData = await response.json();
            if (responseData.success) {
                toastr.success(responseData.message);
                window.location.href = baseUrl + '/admin/listado-usuarios';
            }
        } else {
            const responseData = await response.json();
            const errors = responseData.errors;
            Object.values(errors).forEach(function(value) {
                toastr.error(value);
            });
        }
    } catch (error) {
        console.error("Error de red al intentar enviar la solicitud", error);
        document.querySelector('#create-user-dashboard-button').disabled = false;
        document.querySelector('#create-user-dashboard-button').innerText = 'Crear Usuario';
    }
}



document.addEventListener('DOMContentLoaded', function () {
    const createUserForm = document.querySelector('#create-user-dashboard-form');
    if (createUserForm) {
        createUserForm.addEventListener('submit', function (event) {
            event.preventDefault();
            
            let action = this.getAttribute('action');
            let method = this.getAttribute('method');
            let data = new FormData(this);

            createUserFromDashboard(action, method, data);
    });
}})


if (document.body.id === 'admin-users-edit') {
    console.log('Code specific to User/Edit');

    const editUserForm = document.querySelector('#edit-user-form');
    if (editUserForm) {
        editUserForm.addEventListener('submit', function(event) {
            event.preventDefault();
    
            let action = this.getAttribute('action');
            let method = this.getAttribute('method');
            const formData = new FormData(this);
    
            updateUser(action, method, formData);
        });
    }

    async function updateUser(action, method, data) {
        const submitButton = document.querySelector('#edit-user-button');

        try {
            submitButton.disabled = true;
            submitButton.innerText = "Actualizando...";

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(action, {
                method: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: data
            });

            if (response.ok) {
                const responseData = await response.json();
                if (responseData.success) {
                    toastr.success(responseData.message);
                    location.reload();
                }
            } else {
                const responseData = await response.json();
                const errors = responseData.errors;
                Object.values(errors).forEach(function(value) {
                    toastr.error(value);
                });
            }

        } catch (error) {
            console.error("Error de red al intentar enviar la solicitud", error);
            toastr.error("Error al intentar actualizar el usuario");
        } finally {
            submitButton.disabled = false;
            submitButton.innerText = 'Actualizar Usuario';
        };
        
    }

}


// Body #admin-user-index
if (document.body.id === 'admin-users-index') {
    console.log('Code specific to User/Index');

    async function deleteUser(userId) {
        const deleteButton = document.querySelector(`#deleteUserAnchor-${userId}`);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        try {
            deleteButton.classList.add('disabled');
            deleteButton.textContent = 'Eliminando...';
    
            const formData = new FormData();
            formData.append('_method', 'DELETE');
    
            const response = await fetch(`eliminar-usuario/${userId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData
            });
    
            if (response.ok) {
                const responseData = await response.json();
                if (responseData.success) {
                    sessionStorage.setItem('deleteUserMessage', responseData.message);
                    window.location.reload();
                    return; 
                }
            } else {
                const responseData = await response.json();
                if (responseData.errors) {
                    Object.values(responseData.errors).forEach(function(value) {
                        toastr.error(value);
                    });
                } else {
                    toastr.error(responseData.message || "Error al eliminar el usuario");
                }
            }
        } catch (error) {
            console.error("Error de red al intentar eliminar el usuario", error);
            toastr.error("Error al intentar eliminar el usuario");
        } finally {
            deleteButton.classList.remove('disabled');
            deleteButton.textContent = 'Eliminar';
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        const savedMessage = sessionStorage.getItem('deleteUserMessage');
        if (savedMessage) {
            toastr.success(savedMessage);
            sessionStorage.removeItem('deleteUserMessage');
        }
    
        document.querySelectorAll('[id^="deleteUserAnchor-"]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const userId = button.id.split('-')[1];
                deleteUser(userId);
            });
        });
    });
}


// User -> index -> delete users modal
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        const openModalBtn = event.target.closest('[id^="deleteUserModalAnchor-"]');
        if (openModalBtn) {
            const userId = openModalBtn.id.split('-').pop();
            const modal = document.getElementById(`deleteUserModal-${userId}`);
            if (modal) {
                modal.style.display = 'block';
            } else {
                console.error(`Modal con ID deleteUserModal-${userId} no encontrado`);
            }
        }
    });

    document.body.addEventListener('click', function(event) {
        if (event.target.classList.contains('deleteUserModal') || 
            event.target.classList.contains('cancel-button')) {
            const modal = event.target.closest('.modal');
            if (modal) {
                closeModal(modal);
            }
        }
    });

    window.addEventListener('click', function(event) {
        const modal = event.target.closest('.modal');
        if (modal && event.target === modal) {
            closeModal(modal);
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                if (modal.style.display === 'block') {
                    closeModal(modal);
                }
            });
        }
    });

    function closeModal(modal) {
        modal.style.display = 'none';
    }

    document.body.addEventListener('click', function(event) {
        const modalContent = event.target.closest('.modal-content');
        if (modalContent) {
            event.stopPropagation();
        }
    });
});