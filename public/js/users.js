console.log('users called (public)');
console.log('baseUrl:' + baseUrl);


async function loginUser(action, method, data) {

    document.querySelector('#login-button').disabled = true;
    document.querySelector('#login-button').innerText = 'Ingresando...';

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

        document.querySelector('#login-button').disabled = false;
        document.querySelector('#login-button').innerText = 'Ingresar';

        if (response.ok) {
            const responseData = await response.json();
            if (responseData.success) {
                window.location.href = baseUrl + '/admin'
            } else {
                toastr.error(responseData.message);
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
        document.querySelector('#login-button').disabled = false;
        document.querySelector('#login-button').innerText = 'Ingresar';
    }
}


document.addEventListener('DOMContentLoaded', function () {
    const createUserForm = document.querySelector('#login-form');
    if (createUserForm) {
        createUserForm.addEventListener('submit', function (event) {
            event.preventDefault();
            
            let action = this.getAttribute('action');
            let method = this.getAttribute('method');
            let data = new FormData(this);

            loginUser(action, method, data);
        });
}})




