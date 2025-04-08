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
                document.querySelector('#create-user-dashboard-form').reset();
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
