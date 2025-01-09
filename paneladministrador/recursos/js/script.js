
//eliminar producto
function confirmDeleteProducto(productoId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnProducto').off('click').on('click', function() {
        $.ajax({
            url: 'eliminarproducto.php',
            type: 'POST',
            data: {
                action: 'delete',
                producto_id: productoId
            },
            success: function(response) {
                // console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#producto-' + productoId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
    });
}

//eliminar oferta
function confirmDeleteOferta(ofertaId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnOferta').off('click').on('click', function() {
        $.ajax({
            url: 'eliminaroferta.php',
            type: 'POST',
            data: {
                action: 'delete',
                oferta_id: ofertaId
            },
            success: function(response) {
                // console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#oferta-' + ofertaId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
    });
}

//eliminar administrador
function confirmDeleteAdministrador(administradorId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnAdministrador').off('click').on('click', function() {
        $.ajax({
            url: 'eliminaradministrador.php',
            type: 'POST',
            data: {
                action: 'delete',
                administrador_id: administradorId
            },
            success: function(response) {
                // console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#administrador-' + administradorId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
    });
}

//eliminar color
function confirmDeleteColor(colorId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnColor').off('click').on('click', function() {
        $.ajax({
            url: 'eliminarcolor.php',
            type: 'POST',
            data: {
                action: 'delete',
                color_id: colorId
            },
            success: function(response) {
                // console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#color-' + colorId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
    });
}

//eliminar categoria
function confirmDeleteCategoria(categoriaId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnCategoria').off('click').on('click', function() {
        $.ajax({
            url: 'eliminarcategoria.php',
            type: 'POST',
            data: {
                action: 'delete',
                categoria_id: categoriaId
            },
            success: function(response) {
                // console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#categoria-' + categoriaId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
    });
}

//eliminar marca
function confirmDeleteMarca(marcaId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnMarca').off('click').on('click', function() {
        $.ajax({
            url: 'eliminarmarca.php',
            type: 'POST',
            data: {
                action: 'delete',
                marca_id: marcaId
            },
            success: function(response) {
                console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#marca-' + marcaId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
    });
}
//eliminar cliente
function confirmDeleteCliente(clienteId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnCliente').off('click').on('click', function() {
        $.ajax({
            url: 'eliminarcliente.php',
            type: 'POST',
            data: {
                action: 'delete',
                cliente_id: clienteId
            },
            success: function(response) {
                // console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#cliente-' + clienteId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
    });
}

//eliminar talla
function confirmDeleteTalla(tallaId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnTalla').off('click').on('click', function() {
        $.ajax({
            url: 'eliminartalla.php',
            type: 'POST',
            data: {
                action: 'delete',
                talla_id: tallaId
            },
            success: function(response) {
                // console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#talla-' + tallaId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
    });
}

//eliminar usuario
function confirmDeleteUsuario(usuarioId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnUsuario').off('click').on('click', function() {
        $.ajax({
            url: 'eliminarusuario.php',
            type: 'POST',
            data: {
                action: 'delete',
                usuario_id: usuarioId
            },
            success: function(response) {
                // console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#usuario-' + usuarioId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
    });
}
function confirmDeleteResenha(resenhaId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnResenha').off('click').on('click', function() {
        $.ajax({
            url: 'eliminarresenha.php',
            type: 'POST',
            data: {
                action: 'delete',
                resenha_id: resenhaId
            },
            success: function(response) {
                console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#resenha-' + resenhaId).remove();
                } else {
                    alert(result.error);
                }
            }
        });
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