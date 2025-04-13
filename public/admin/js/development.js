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



// Body #admin-developments-edit
if (document.body.id === 'admin-developments-edit') {
    console.log('Code specific to Development/Edit');
    
    let developmentFiles = [];
    let deletedImages = [];
    
    const previewContainer = document.getElementById('image-preview-container');
    const fileInput = document.getElementById('images');
    
    const sortableList = new Sortable(previewContainer, {
        animation: 150,
        onEnd: function(evt) {
            const items = Array.from(previewContainer.children);
            
            const newdevelopmentFiles = [];
            let newFileCounter = 0;
            
            items.forEach(item => {
                if (item.dataset.imageType === 'new') {
                    newdevelopmentFiles.push(developmentFiles[parseInt(item.dataset.fileIndex)]);
                    item.dataset.fileIndex = newFileCounter++;
                }
            });
            developmentFiles = newdevelopmentFiles;
            
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
                <img src="${file.preview}" alt="Nueva imagen de emprendimiento" class="preview-thumbnail ${randomClass}">
                <input type="hidden" name="image_order[]" value="${index}">
                <input type="hidden" name="image_class[]" value="${randomClass}">
                <a href="javascript:void(0)" class="btn-remove-development-image">X</a>
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
            developmentFiles.splice(index, 1);
        }
        
        previewContainer.removeChild(element);
        
        const newImages = previewContainer.querySelectorAll('.image-item[data-image-type="new"]');
        newImages.forEach((item, i) => {
            item.dataset.fileIndex = i;
        });
        
        updateImageOrder();
    }

    previewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-development-image')) {
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
            developmentFiles.push(file);
        });

        validFiles.forEach((file, i) => {
            const reader = new FileReader();

            reader.onload = function(e) {
                file.preview = e.target.result;
                const currentIndex = developmentFiles.length - validFiles.length + i;
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

    const editDevelopmentForm = document.querySelector('#edit-development-form');
    if (editDevelopmentForm) {
        editDevelopmentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            // Limpiar las imágenes existentes del FormData
            for (let i = formData.getAll('images[]').length - 1; i >= 0; i--) {
                formData.delete('images[]');
            }
            
            // Agregar las nuevas imágenes
            developmentFiles.forEach((file, index) => {
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
    
            updateDevelopment(action, method, formData);
        });
    }
    

    async function updateDevelopment(action, method, data) {
        const submitButton = document.querySelector('#edit-development-button');
        
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
            toastr.error("Error al intentar actualizar el emprendimiento");
        } finally {
            submitButton.disabled = false;
            submitButton.innerText = 'Guardar';
        }
    }
}




// Body #admin-developments-index
if (document.body.id === 'admin-developments-index') {
    console.log('Code specific to Development/Index');

    async function deleteDevelopment(developmentId) {
        const deleteButton = document.querySelector(`#deleteDevelopmentAnchor-${developmentId}`);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        try {
            deleteButton.classList.add('disabled');
            deleteButton.textContent = 'Eliminando...';
    
            const formData = new FormData();
            formData.append('_method', 'DELETE');
    
            const response = await fetch(`eliminar-emprendimiento/${developmentId}`, {
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
                    sessionStorage.setItem('deleteDevelopmentMessage', responseData.message);
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
                    toastr.error(responseData.message || "Error al eliminar el emprendimiento");
                }
            }
        } catch (error) {
            console.error("Error de red al intentar eliminar el emprendimiento", error);
            toastr.error("Error al intentar eliminar el emprendimiento");
        } finally {
            deleteButton.classList.remove('disabled');
            deleteButton.textContent = 'Eliminar';
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        const savedMessage = sessionStorage.getItem('deleteDevelopmentMessage');
        if (savedMessage) {
            toastr.success(savedMessage);
            sessionStorage.removeItem('deleteDevelopmentMessage');
        }
    
        document.querySelectorAll('[id^="deleteDevelopmentAnchor-"]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const developmentId = button.id.split('-')[1];
                deleteDevelopment(developmentId);
            });
        });
    });
}


// Developments -> index -> delete development modal
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        const openModalBtn = event.target.closest('[id^="deleteDevelopmentModalAnchor-"]');
        if (openModalBtn) {
            const developmentId = openModalBtn.id.split('-').pop();
            const modal = document.getElementById(`deleteDevelopmentModal-${developmentId}`);
            if (modal) {
                modal.style.display = 'block';
            } else {
                console.error(`Modal con ID deleteDevelopmentModal-${developmentId} no encontrado`);
            }
        }
    });

    document.body.addEventListener('click', function(event) {
        if (event.target.classList.contains('deleteDevelopmentModal') || 
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