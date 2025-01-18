//Limpiar filtros
function limpiarFiltros() {
    const form = document.getElementById('filterForm');
    form.nombre.value = '';
    form.categoria.selectedIndex = 0;
    form.marca.selectedIndex = 0;
    form.submit();
}
function openProductModal(id, name, description, image, price, category, brand) {
    document.getElementById('modalProductName').innerText = name;
    document.getElementById('modalProductDescription').innerText = description;
    document.getElementById('modalProductImage').src = image;
    document.getElementById('modalProductPrice').innerText = ' S/ '+ price.toFixed(2) ; // Formato de precio
    document.getElementById('modalProductCategory').innerText = category; // Agregar categoría
    document.getElementById('modalProductBrand').innerText = brand; // Agregar marca
    
    var myModal = new bootstrap.Modal(document.getElementById('productModal'));
    myModal.show();
}
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
                    $('#deleteModal').modal('hide');
                    // alert(result.error);
                     // Mostrar el mensaje de error en la alerta
                     $('.alert-danger').remove();
                     $('.alert-success').remove();
                     $('.alert-fk').prepend('<div class="alert alert-danger alert-dismissible alert-outline fade show">' + result.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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
                    $('#deleteModal').modal('hide');
                   // alert(result.error);
                    // Mostrar el mensaje de error en la alerta
                    $('.alert-danger').remove();
                    $('.alert-success').remove();
                    $('.alert-fk').prepend('<div class="alert alert-danger alert-dismissible alert-outline fade show">' + result.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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
                    $('#deleteModal').modal('hide');
                    // alert(result.error);
                     // Mostrar el mensaje de error en la alerta
                     $('.alert-danger').remove();
                     $('.alert-success').remove();
                     $('.alert-fk').prepend('<div class="alert alert-danger alert-dismissible alert-outline fade show">' + result.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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
                    $('#deleteModal').modal('hide');
                    // Mostrar el mensaje de error en la alerta
                    $('.alert-danger').remove();
                    $('.alert-success').remove();
                    $('.alert-fk').prepend('<div class="alert alert-danger alert-dismissible alert-outline fade show">' + result.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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
                    $('#deleteModal').modal('hide');
                    // Mostrar el mensaje de error en la alerta
                    $('.alert-danger').remove();
                    $('.alert-success').remove();
                    $('.alert-fk').prepend('<div class="alert alert-danger alert-dismissible alert-outline fade show">' + result.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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
                    $('#deleteModal').modal('hide');
                    // Mostrar el mensaje de error en la alerta
                    $('.alert-danger').remove();
                    $('.alert-success').remove();
                    $('.alert-fk').prepend('<div class="alert alert-danger alert-dismissible alert-outline fade show">' + result.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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

//eliminar reseña
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

//eliminar stock
function confirmDeleteStock(stockId) {
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnStock').off('click').on('click', function() {
        $.ajax({
            url: 'eliminarstock.php',
            type: 'POST',
            data: {
                action: 'delete',
                stock_id: stockId
            },
            success: function(response) {
                console.log(response); // Agregar este console.log para depurar la respuesta
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#stock-' + stockId).remove();
                } else {
                    $('#deleteModal').modal('hide');
                    // Mostrar el mensaje de error en la alerta
                    $('.alert-danger').remove();
                    $('.alert-success').remove();
                    $('.alert-fk').prepend('<div class="alert alert-danger alert-dismissible alert-outline fade show">' + result.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            }
        });
    });
}

//filtros de buscar y filtrar (todos)
$(document).ready(function() {
    let searchTimeout = null;

    //filtro de buscar para todas las tablas
    $('#search').on('keyup', function() {
        clearTimeout(searchTimeout);
        var value = $(this).val().toLowerCase();
        searchTimeout = setTimeout(function() {
            var url = '?search=' + value;
            if ($('#filterCategory').val()) {
                url += '&filterCategory=' + $('#filterCategory').val();
            }
            if ($('#filterState').val()) {
                url += '&filterState=' + $('#filterState').val();
            }
            if ($('#order_dir').val()) {
                url += '&order_dir=' + $('#order_dir').val();
            }
            window.location.href = url + '#example';
        }, 800);
    });

    //filtros independientes(varia para cada tabla) para todas las tablas
    $('#filterCategory, #filterState, #order_dir').on('change', function() {
        var url = '?search=' + $('#search').val();
        if ($('#filterCategory').val()) {
            url += '&filterCategory=' + $('#filterCategory').val();
        }
        if ($('#filterState').val()) {
            url += '&filterState=' + $('#filterState').val();
        }
        if ($('#order_dir').val()) {
            url += '&order_dir=' + $('#order_dir').val();
        }
        window.location.href = url + '#example';
    });

});

//Incrementar y decremnetar input de cantidad
function increment() {
    var input = document.getElementById('stoCantidad');
    input.value = parseInt(input.value) + 1;
}

function decrement() {
    var input = document.getElementById('stoCantidad');
    if (input.value > 0) {
        input.value = parseInt(input.value) - 1;
    }
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

