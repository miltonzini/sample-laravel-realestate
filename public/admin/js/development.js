console.log('development.js called (dashboard)');

if (document.body.id === 'admin-developments-create') {
    console.log('Code specific to Development/Create');
    
    let developmentFiles = [];
    
    const previewContainer = document.getElementById('image-preview-container');
    const fileInput = document.getElementById('images');
    
    const sortableList = new Sortable(previewContainer, {
        animation: 150,
        onEnd: function(evt) {
            updateImageOrder();
            const newOrder = Array.from(previewContainer.children).map(item => 
                parseInt(item.dataset.fileIndex)
            );
            developmentFiles = newOrder.map(index => developmentFiles[index]);
        }
    });

    const loadingElement = document.createElement('div');
    loadingElement.className = 'loading-overlay hidden';
    loadingElement.innerHTML = `
        <div class="loading-content">
            <div class="spinner"></div>
            <p class="loading-text">Cargando imágenes...</p>
        </div>
    `;
    previewContainer.parentNode.insertBefore(loadingElement, previewContainer.nextSibling);

    function updateImageOrder() {
        const imageItems = previewContainer.querySelectorAll('.image-item');
        imageItems.forEach((item, index) => {
            const input = item.querySelector('input[name="image_order[]"]');
            if (input) {
                input.value = index;
            }
        });
    }

    function removeFile(index) {
        developmentFiles.splice(index, 1);
        updateFileInput();
    }

    function updateFileInput() {
        const formData = new FormData();
        developmentFiles.forEach((file, index) => {
            formData.append(`images[]`, file);
        });
        
        fileInput.value = '';
    }

    previewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-development-image')) {
            const imageItem = e.target.closest('.image-item');
            if (imageItem) {
                const index = parseInt(imageItem.dataset.fileIndex);
                removeFile(index);
                previewContainer.removeChild(imageItem);
                
                Array.from(previewContainer.children).forEach((item, i) => {
                    item.dataset.fileIndex = i;
                });
                
                updateImageOrder();
            }
        }
    });
    
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length === 0) return;

        loadingElement.classList.remove('hidden');
        let loadedImages = 0;
        
        const validFiles = files.filter(file => {
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                toastr.error(`${file.name} no es un tipo de archivo válido`);
                return false;
            }

            if (file.size > 5 * 1024 * 1024) {
                toastr.error(`${file.name} excede el tamaño máximo de 5MB`);
                return false;
            }

            return true;
        });

        if (validFiles.length === 0) {
            loadingElement.classList.add('hidden');
            return;
        }

        validFiles.forEach(file => {
            developmentFiles.push(file);
        });

        validFiles.forEach((file, i) => {
            const reader = new FileReader();

            reader.onload = function(e) {
                const currentIndex = developmentFiles.length - validFiles.length + i;
                const imgElement = document.createElement('div');
                imgElement.classList.add('image-item');
                imgElement.dataset.fileIndex = currentIndex;
                imgElement.innerHTML = `
                    <div class="thumbnail-wrapper">
                        <img src="${e.target.result}" alt="Image Preview" class="preview-thumbnail">
                        <input type="hidden" name="image_order[]" value="${currentIndex}">
                        <a href="javascript:void(0)" class="btn-remove-development-image">X</a>
                        <span class="file-name">
                            ${file.name.length > 18 ? file.name.substring(0, 18) + '...' : file.name}
                        </span>
                    </div>
                `;
                previewContainer.appendChild(imgElement);
                updateImageOrder();

                loadedImages++;
                
                if (loadedImages === validFiles.length) {
                    setTimeout(() => {
                        loadingElement.classList.add('hidden');
                    }, 500);
                }
            };

            reader.readAsDataURL(file);
        });
    });

    const createDevelopmentForm = document.querySelector('#create-development-form');
    if (createDevelopmentForm) {
        createDevelopmentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            for (let i = formData.getAll('images[]').length - 1; i >= 0; i--) {
                formData.delete('images[]');
            }
            
            developmentFiles.forEach((file, index) => {
                formData.append('images[]', file);
            });

            let action = this.getAttribute('action');
            let method = this.getAttribute('method');

            createDevelopment(action, method, formData);
        });
    }

    async function createDevelopment(action, method, data) {
        const submitButton = document.querySelector('#create-development-button');
        
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
                    window.location.href = baseUrl + '/admin/listado-emprendimientos';
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
            toastr.error("Error al intentar guardar el emprendimiento");
        } finally {
            submitButton.disabled = false;
            submitButton.innerText = 'Guardar';
        }
    }
}