console.log('property.js called (dashboard)');

// Body #admin-properties-create -> Show images thumbail previews and manage order
if (document.body.id === 'admin-properties-create') {
    console.log('Code specific to Property/Create');
    
    let propertyFiles = [];
    
    const previewContainer = document.getElementById('image-preview-container');
    const fileInput = document.getElementById('images');
    
    const sortableList = new Sortable(previewContainer, {
        animation: 150,
        onEnd: function(evt) {
            updateImageOrder();
            const newOrder = Array.from(previewContainer.children).map(item => 
                parseInt(item.dataset.fileIndex)
            );
            propertyFiles = newOrder.map(index => propertyFiles[index]);
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
        propertyFiles.splice(index, 1);
        updateFileInput();
    }

    function updateFileInput() {
        const formData = new FormData();
        propertyFiles.forEach((file, index) => {
            formData.append(`images[]`, file);
        });
        
        fileInput.value = '';
    }

    previewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-property-image')) {
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
            propertyFiles.push(file);
        });

        validFiles.forEach((file, i) => {
            const reader = new FileReader();

            reader.onload = function(e) {
                const currentIndex = propertyFiles.length - validFiles.length + i;
                const imgElement = document.createElement('div');
                imgElement.classList.add('image-item');
                imgElement.dataset.fileIndex = currentIndex;
                imgElement.innerHTML = `
                    <div class="thumbnail-wrapper">
                        <img src="${e.target.result}" alt="Image Preview" class="preview-thumbnail">
                        <input type="hidden" name="image_order[]" value="${currentIndex}">
                        <a href="javascript:void(0)" class="btn-remove-property-image">X</a>
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

    const createPropertyForm = document.querySelector('#create-property-form');
    if (createPropertyForm) {
        createPropertyForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            for (let i = formData.getAll('images[]').length - 1; i >= 0; i--) {
                formData.delete('images[]');
            }
            
            propertyFiles.forEach((file, index) => {
                formData.append('images[]', file);
            });

            let action = this.getAttribute('action');
            let method = this.getAttribute('method');

            createProperty(action, method, formData);
        });
    }

    async function createProperty(action, method, data) {
        const submitButton = document.querySelector('#create-property-button');
        
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
                    window.location.href = baseUrl + '/admin/listado-propiedades';
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
            toastr.error("Error al intentar guardar la propiedad");
        } finally {
            submitButton.disabled = false;
            submitButton.innerText = 'Guardar';
        }
    }
}





// Body #admin-properties-edit -> Show images thumbail previews and manage order
if (document.body.id === 'admin-properties-edit') {
    console.log('Code specific to Property/Edit');
    
    let propertyFiles = [];
    let deletedImages = [];
    
    const previewContainer = document.getElementById('image-preview-container');
    const fileInput = document.getElementById('images');
    
    const sortableList = new Sortable(previewContainer, {
        animation: 150,
        onEnd: function(evt) {
            const items = Array.from(previewContainer.children);
            
            const newPropertyFiles = [];
            let newFileCounter = 0;
            
            items.forEach(item => {
                if (item.dataset.imageType === 'new') {
                    newPropertyFiles.push(propertyFiles[parseInt(item.dataset.fileIndex)]);
                    item.dataset.fileIndex = newFileCounter++;
                }
            });
            propertyFiles = newPropertyFiles;
            
            items.forEach((item, index) => {
                const orderInput = item.querySelector('input[name="image_order[]"]');
                if (orderInput) {
                    if (item.dataset.imageType === 'existing') {
                        orderInput.value = item.dataset.imageId;
                    } else {
                        orderInput.value = 'new-' + item.dataset.fileIndex;
                    }
                }
            });
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

    function createImageElement(file, index) {
        const imgElement = document.createElement('div');
        imgElement.classList.add('image-item');
        imgElement.dataset.fileIndex = index;
        imgElement.dataset.imageType = 'new';
        
        const randomClass = 'img-' + Math.random().toString(36).substr(2, 9);
        
        imgElement.innerHTML = `
            <div class="thumbnail-wrapper">
                <img src="${file.preview}" alt="Nueva imagen de propiedad" class="preview-thumbnail ${randomClass}">
                <input type="hidden" name="image_order[]" value="${index}">
                <input type="hidden" name="image_class[]" value="${randomClass}">
                <a href="javascript:void(0)" class="btn-remove-property-image">X</a>
                <span class="file-name">${file.name.substring(0, 18)}...</span>
            </div>
        `;
        return imgElement;
    }

    function updateImageOrder() {
        const imageItems = previewContainer.querySelectorAll('.image-item');
        imageItems.forEach((item, index) => {
            const input = item.querySelector('input[name="image_order[]"]');
            if (input) {
                input.value = index;
            }
        });
    }

    function removeFile(element) {
        const imageType = element.dataset.imageType;
        
        if (imageType === 'existing') {
            const imageId = element.dataset.imageId;
            deletedImages.push(imageId);
        } else {
            const index = parseInt(element.dataset.fileIndex);
            propertyFiles.splice(index, 1);
        }
        
        previewContainer.removeChild(element);
        
        const newImages = previewContainer.querySelectorAll('.image-item[data-image-type="new"]');
        newImages.forEach((item, i) => {
            item.dataset.fileIndex = i;
        });
        
        updateImageOrder();
    }

    previewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-property-image')) {
            const imageItem = e.target.closest('.image-item');
            if (imageItem) {
                removeFile(imageItem);
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
            propertyFiles.push(file);
        });

        validFiles.forEach((file, i) => {
            const reader = new FileReader();

            reader.onload = function(e) {
                file.preview = e.target.result;
                const currentIndex = propertyFiles.length - validFiles.length + i;
                const imgElement = createImageElement(file, currentIndex);
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

    const editPropertyForm = document.querySelector('#edit-property-form');
    if (editPropertyForm) {
        editPropertyForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            // Limpiar las imágenes existentes del FormData
            for (let i = formData.getAll('images[]').length - 1; i >= 0; i--) {
                formData.delete('images[]');
            }
            
            // Agregar las nuevas imágenes
            propertyFiles.forEach((file, index) => {
                formData.append('images[]', file);
            });
    
            // Limpiar y agregar las imágenes eliminadas como array
            formData.delete('deleted_images[]');
            deletedImages.forEach(imageId => {
                formData.append('deleted_images[]', imageId);
            });
            
            // Obtener todos los elementos de imagen y su orden
            const imageItems = Array.from(previewContainer.querySelectorAll('.image-item'));
            
            // Limpiar cualquier image_order[] existente
            formData.delete('image_order[]');
            
            // Agregar el orden de las imágenes como array
            imageItems.forEach((item, index) => {
                if (item.dataset.imageType === 'existing') {
                    // Para imágenes existentes, usar el ID
                    formData.append('image_order[]', item.dataset.imageId);
                } else {
                    // Para imágenes nuevas, usar 'new-' + índice
                    formData.append('image_order[]', 'new-' + item.dataset.fileIndex);
                }
            });
    
            let action = this.getAttribute('action');
            let method = this.getAttribute('method');
    
            updateProperty(action, method, formData);
        });
    }
    

    async function updateProperty(action, method, data) {
        const submitButton = document.querySelector('#edit-property-button');
        
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
            toastr.error("Error al intentar actualizar la propiedad");
        } finally {
            submitButton.disabled = false;
            submitButton.innerText = 'Guardar';
        }
    }
}

