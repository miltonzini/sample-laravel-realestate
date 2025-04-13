console.log('blog.js called (dashboard)');

// Body #admin-create-post -> Show image thumbail and manage order events
if (document.body.id === 'admin-create-post') {
    console.log('Code specific to Blog/Create');
    
    let postFile = null;
    
    const previewContainer = document.getElementById('image-preview-container');
    const fileInput = document.getElementById('images');  // Cambié el selector de 'image' a 'images'
    
    const loadingElement = document.createElement('div');
    loadingElement.className = 'loading-overlay hidden';
    loadingElement.innerHTML = `
        <div class="loading-content">
            <div class="spinner"></div>
            <p class="loading-text">Cargando imagen...</p>
        </div>
    `;
    previewContainer.parentNode.insertBefore(loadingElement, previewContainer.nextSibling);

    function removeFile() {
        postFile = null;
        fileInput.value = '';
        previewContainer.innerHTML = '';
    }

    previewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-post-image')) {
            removeFile();
        }
    });
    
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length === 0) return;

        const file = files[0]; // Solo tomar el primer archivo
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

        if (!validTypes.includes(file.type)) {
            toastr.error(`${file.name} no es un tipo de archivo válido`);
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            toastr.error(`${file.name} excede el tamaño máximo de 5MB`);
            return;
        }

        loadingElement.classList.remove('hidden');

        const reader = new FileReader();

        reader.onload = function(e) {
            postFile = file;
            previewContainer.innerHTML = `
                <div class="image-item">
                    <div class="thumbnail-wrapper">
                        <img src="${e.target.result}" alt="Image Preview" class="preview-thumbnail">
                        <a href="javascript:void(0)" class="btn-remove-post-image">X</a>
                        <span class="file-name">
                            ${file.name.length > 18 ? file.name.substring(0, 18) + '...' : file.name}
                        </span>
                    </div>
                </div>
            `;

            setTimeout(() => {
                loadingElement.classList.add('hidden');
            }, 500);
        };

        reader.readAsDataURL(file);
    });

    const createPostForm = document.querySelector('#create-post-form');
    const submitButton = document.querySelector('#create-post-button');
    
    if (createPostForm) {
        createPostForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            // Eliminar cualquier imagen previa
            formData.delete('image');
            
            // Agregar la imagen única si existe
            if (postFile) {
                formData.append('image', postFile);
            }

            let action = this.getAttribute('action');
            let method = this.getAttribute('method');

            createPost(action, method, formData);
        });
    }

    async function createPost(action, method, data) {
        try {
            submitButton.disabled = true;
            submitButton.innerText = 'Guardando...';
    
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
                    window.location.href = baseUrl + '/admin/listado-posts';
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
            toastr.error("Error al intentar guardar el post");
        } finally {
            submitButton.disabled = false;
            submitButton.innerText = 'Guardar';
        }
    }
}





// Autocompleta url_slug field
document.addEventListener('DOMContentLoaded', function () {
    const postTitleInput = document.querySelector('input[name="title"]');
    const urlSlugInput = document.querySelector('input[name="slug"]');


    // Url Slug
    function generateSlug(text) {
        const from = "áéíóúüñÁÉÍÓÚÜÑ";
        const to = "aeiouunAEIOUUN";
        const mapping = {};
    
        for (let i = 0; i < from.length; i++) {
            mapping[from[i]] = to[i];
        }
    
        const normalizedText = text
            .split('')
            .map(char => mapping[char] || char) 
            .join('');
    
        return normalizedText
            .toString()
            .toLowerCase()
            .trim()
            .replace(/[\s\W-]+/g, '-') 
            .replace(/^-+|-+$/g, ''); 
    }

    postTitleInput.addEventListener('input', function () {
        const generatedSlug = generateSlug(postTitleInput.value);

        if (urlSlugInput.value === '' || urlSlugInput.value === generateSlug(urlSlugInput.value)) {
            urlSlugInput.value = generatedSlug;
        }
    });

    urlSlugInput.addEventListener('input', function () {
        const modifiedSlug = generateSlug(urlSlugInput.value);
        urlSlugInput.value = modifiedSlug;
    });
});
