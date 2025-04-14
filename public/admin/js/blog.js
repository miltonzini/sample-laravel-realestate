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


// Body #admin-edit-post -> Show image thumbnail and manage order events
if (document.body.id === 'admin-edit-post') {
    console.log('Code specific to Blog/Edit');
    
    let postFile = null;
    let existingImage = false;
    
    const previewContainer = document.getElementById('image-preview-container');
    const fileInput = document.getElementById('images');
    
    if (previewContainer.querySelector('.image-item')) {
        existingImage = true;
    }
    
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
        
        // If removing a newly uploaded file
        if (!existingImage) {
            previewContainer.innerHTML = '';
        } else {
            const existingImageItem = previewContainer.querySelector('.image-item[data-image-type="existing"]');
            if (existingImageItem) {
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_image';
                deleteInput.value = existingImageItem.getAttribute('data-image-id');
                previewContainer.appendChild(deleteInput);
                
                existingImageItem.remove();
                existingImage = false;
            }
        }
    }

    previewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-property-image') || 
            e.target.classList.contains('btn-remove-post-image')) {
            removeFile();
        }
    });
    
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length === 0) return;

        const file = files[0];
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
            
            if (existingImage) {
                const existingImageItem = previewContainer.querySelector('.image-item[data-image-type="existing"]');
                if (existingImageItem) {
                    const replaceInput = document.createElement('input');
                    replaceInput.type = 'hidden';
                    replaceInput.name = 'replace_image';
                    replaceInput.value = existingImageItem.getAttribute('data-image-id');
                    previewContainer.appendChild(replaceInput);
                    
                    existingImageItem.remove();
                }
            }
            
            previewContainer.innerHTML = `
                <div class="image-item" data-image-type="new">
                    <div class="thumbnail-wrapper">
                        <img src="${e.target.result}" alt="Image Preview" class="preview-thumbnail">
                        <a href="javascript:void(0)" class="btn-remove-post-image">X</a>
                        <span class="file-name">
                            ${file.name.length > 18 ? file.name.substring(0, 18) + '...' : file.name}
                        </span>
                    </div>
                </div>
            `;

            existingImage = false;

            setTimeout(() => {
                loadingElement.classList.add('hidden');
            }, 500);
        };

        reader.readAsDataURL(file);
    });

    const editPostForm = document.querySelector('#edit-post-form');
    const submitButton = document.querySelector('#edit-post-button');
    
    if (editPostForm) {
        editPostForm.addEventListener('submit', function(event) {
            event.preventDefault();

            if (CKEDITOR.instances['post-body-textarea']) {
                document.getElementById('post-body-textarea').value = CKEDITOR.instances['post-body-textarea'].getData();
            }
            
            const formData = new FormData(this);
            
            formData.delete('image');
            
            if (postFile) {
                formData.append('image', postFile);
            }

            let action = this.getAttribute('action');
            let method = this.getAttribute('method');

            updatePost(action, method, formData);
        });
    }

    async function updatePost(action, method, data) {
        try {
            submitButton.disabled = true;
            submitButton.innerText = 'Actualizando...';
    
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            if (method.toLowerCase() === 'put' || method.toLowerCase() === 'patch') {
                data.append('_method', method);
                method = 'POST';
            }
            
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
                    toastr.success(responseData.message || 'Post actualizado correctamente');
                    window.location.href = baseUrl + '/admin/listado-posts';
                } else {
                    toastr.error(responseData.message || 'Hubo un error al actualizar el post');
                }
            } else {
                const responseData = await response.json();
                if (responseData.errors) {
                    Object.values(responseData.errors).forEach(function(value) {
                        toastr.error(value);
                    });
                } else {
                    toastr.error(responseData.message || 'Error al actualizar el post');
                }
            }
        } catch (error) {
            console.error("Error de red al intentar enviar la solicitud", error);
            toastr.error("Error al intentar actualizar el post");
        } finally {
            submitButton.disabled = false;
            submitButton.innerText = 'Actualizar';
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



// Body #admin-post-index
if (document.body.id === 'admin-blog-index') {
    console.log('Code specific to Blog/Index');

    async function deletePost(postId) {
        const deleteButton = document.querySelector(`#deletePostAnchor-${postId}`);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        try {
            deleteButton.classList.add('disabled');
            deleteButton.textContent = 'Eliminando...';
    
            const formData = new FormData();
            formData.append('_method', 'DELETE');
    
            const response = await fetch(`eliminar-post/${postId}`, {
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
                    sessionStorage.setItem('deletePostMessage', responseData.message);
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
                    toastr.error(responseData.message || "Error al eliminar el post");
                }
            }
        } catch (error) {
            console.error("Error de red al intentar eliminar el post", error);
            toastr.error("Error al intentar eliminar el post");
        } finally {
            deleteButton.classList.remove('disabled');
            deleteButton.textContent = 'Eliminar';
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        const savedMessage = sessionStorage.getItem('deletePostMessage');
        if (savedMessage) {
            toastr.success(savedMessage);
            sessionStorage.removeItem('deletePostMessage');
        }
    
        document.querySelectorAll('[id^="deletePostAnchor-"]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const postId = button.id.split('-')[1];
                deletePost(postId);
            });
        });
    });
}


// Blog -> index -> delete post modal
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        const openModalBtn = event.target.closest('[id^="deletePostModalAnchor-"]');
        if (openModalBtn) {
            const postId = openModalBtn.id.split('-').pop();
            const modal = document.getElementById(`deletePostModal-${postId}`);
            if (modal) {
                modal.style.display = 'block';
            } else {
                console.error(`Modal con ID deletePostModal-${postId} no encontrado`);
            }
        }
    });

    document.body.addEventListener('click', function(event) {
        if (event.target.classList.contains('deletePostModal') || 
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