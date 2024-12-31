//eliminar all
let deleteProductId = null;

function confirmDelete(id) {
    deleteProductId = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

//eliminar producto
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
        if (deleteProductId !== null) {
            window.location.href = 'eliminarproducto.php?id=' + deleteProductId;
        }
    });
}

//eliminar categoria
const confirmDeleteBtnCategory = document.getElementById('confirmDeleteBtnCategory');
if (confirmDeleteBtnCategory) {
    confirmDeleteBtnCategory.addEventListener('click', function() {
        if (deleteProductId !== null) {
            window.location.href = 'eliminarcategoria.php?id=' + deleteProductId;
        }
    });
}
//eliminar oferta
const confirmDeleteBtnOferta = document.getElementById('confirmDeleteBtnOferta');
if (confirmDeleteBtnOferta) {
    confirmDeleteBtnOferta.addEventListener('click', function() {
        if (deleteProductId !== null) {
            window.location.href = 'eliminaroferta.php?id=' + deleteProductId;
        }
    });
}

//eliminar administrador
const confirmDeleteBtnAdmin = document.getElementById('confirmDeleteBtnAdmin');
if (confirmDeleteBtnAdmin) {
    confirmDeleteBtnAdmin.addEventListener('click', function() {
        if (deleteProductId !== null) {
            window.location.href = 'eliminarusuario.php?id=' + deleteProductId;
        }
    });
}

/* buscador por nombre en productos*/
let searchTimeout = null;
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            document.getElementById('filterForm').submit();
        }, 500); // Retraso de 500 ms
    });
}

/* ADMINISTRADOR VER CONTRASEÑA */
const togglePassword = document.getElementById('togglePassword');
if (togglePassword) {
    togglePassword.addEventListener('click', function () {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('mdi-eye-outline');
        this.querySelector('i').classList.toggle('mdi-eye-off-outline');
    });
}

const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
if (toggleConfirmPassword) {
    toggleConfirmPassword.addEventListener('click', function () {
        const confirmPassword = document.getElementById('confirm_password');
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        this.querySelector('i').classList.toggle('mdi-eye-outline');
        this.querySelector('i').classList.toggle('mdi-eye-off-outline');
    });
}
/* ADMINISTRADOR VER CONTRASEÑA */