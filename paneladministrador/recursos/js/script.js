function openProductModal(id, nombre, descripcion, img1, img2, precio, categoria, marca) {
    document.getElementById('modalProductName').innerText = nombre;
    document.getElementById('modalProductDescription').innerText = descripcion;
    document.getElementById('modalProductImage1').src = `../../recursos/uploads/producto/${img1}`;
    document.getElementById('modalProductImage2').src = `../../recursos/uploads/producto/${img2}`;
    document.getElementById('modalProductPrice').innerText = `S/. ${precio.toFixed(2)}`;
    document.getElementById('modalProductCategory').innerText = categoria; 
    document.getElementById('modalProductBrand').innerText = marca;
    // Muestra el modal
    $('#productModal').modal('show');
}
//intercambiar imagenes en productos
function swapImages() {
    const img1 = document.getElementById('modalProductImage1');
    const img2 = document.getElementById('modalProductImage2');
    const tempSrc = img1.src;
    img1.src = img2.src;
    img2.src = tempSrc;
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
function confirmDeleteOfertas(ofertaId) {
    ofertaIdToDelete = ofertaId; // Guardar el ID de la oferta a eliminar
    $('#deleteModal').modal('show');
    $('#confirmDeleteBtnOferta').on('click', function() {
        $.ajax({
            url: 'eliminaroferta.php', // Cambia esta ruta a tu script de eliminación
            type: 'POST',
            data: {
                action: 'delete',
                oferta_id: ofertaIdToDelete
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#deleteModal').modal('hide');
                    $('#oferta-' + ofertaIdToDelete).remove(); // Eliminar la fila de la tabla
                } else {
                    $('#deleteModal').modal('hide');
                    // Mostrar el mensaje de error en la alerta
                    $('.alert-danger').remove();
                    $('.alert-success').remove();
                    $('.alert-fk').prepend('<div class="alert alert-danger alert-dismissible fade show">' + result.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            error: function() {
                $('#deleteModal').modal('hide');
                $('.alert-danger').remove();
                $('.alert-fk').prepend('<div class="alert alert-danger alert-dismissible fade show">Error en la solicitud. Inténtalo de nuevo.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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
            if ($('#filterMarca').val()) {
                url += '&filterMarca=' + $('#filterMarca').val();
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
    $('#filterCategory, #filterState, #order_dir,#filterMarca').on('change', function() {
        var url = '?search=' + $('#search').val();
        if ($('#filterCategory').val()) {
            url += '&filterCategory=' + $('#filterCategory').val();
        }
        if ($('#filterMarca').val()) {
            url += '&filterMarca=' + $('#filterMarca').val();
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

//mostrar tooltip en Stock

document.addEventListener('DOMContentLoaded', () => {
    const tooltip = document.getElementById('stock-tooltip');
    const imgElement = document.getElementById('tooltip-img');
    const categoryElement = document.getElementById('tooltip-category');
    const priceElement = document.getElementById('tooltip-price');
    const marcaElement = document.getElementById('tooltip-marca');

    document.querySelectorAll('td.product-name').forEach(cell => {
        cell.addEventListener('mouseover', event => {
            const imgSrc = `../../recursos/uploads/producto/${cell.getAttribute('data-img')}`;
            const category = cell.getAttribute('data-category');
            const price = cell.getAttribute('data-price');
            const marca = cell.getAttribute('data-marca');


            imgElement.src = imgSrc;
            categoryElement.textContent = `${category}`;
            priceElement.textContent = `$${price}`;
            marcaElement.textContent = `${marca}`;


            tooltip.style.display = 'block';
            tooltip.style.left = `${event.pageX + 10}px`;
            tooltip.style.top = `${event.pageY + 10}px`;
        });

        cell.addEventListener('mouseout', () => {
            tooltip.style.display = 'none';
        });

        cell.addEventListener('mousemove', event => {
            tooltip.style.left = `${event.pageX + 10}px`;
            tooltip.style.top = `${event.pageY + 10}px`;
        });
    });
});

//mostrar tallas dependiendo a la categoria
function filtrarTallas(proId) {
    const talId = document.getElementById('talId');

    if (!proId) {
        talId.innerHTML = '<option value="" selected>Seleccione talla</option>';
        return;
    }

    // Llamada AJAX
    fetch('../../productos/stock/obtener-tallas.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `proId=${proId}`,
    })
        .then((response) => response.json())
        .then((data) => {
            talId.innerHTML = '<option value="" selected>Seleccione talla</option>';
            if (data.length === 0) {
                console.error('No se encontraron tallas para el producto seleccionado.');
                return;
            }

            data.forEach((talla) => {
                talId.innerHTML += `<option value="${talla.talId}">${talla.talNombre}</option>`;
            });
        })
        .catch((error) => console.error('Error:', error));
}

//mostrar la imagen al seleccionar un producto en Stock
function showProductImage(proId) {
    const select = document.getElementById('proId');
    const selectedOption = select.options[select.selectedIndex];
    const imgSrc = selectedOption.getAttribute('data-img-pro');
    const imgContainer = document.getElementById('productImageContainer');
    imgContainer.innerHTML = `<img src='../../recursos/uploads/producto/${imgSrc}' alt='Imagen del Producto' class='img-fluid' id='productImage'style='width: 50%; height: auto;'>`;
const productImage = document.getElementById('productImage');
productImage.onerror = function() {
    this.src = '../../recursos/images/imagen-no-encontrada/img-not-found.jpg';
};
}

//al seleccionar un color se ve la representaacion en Stock
function updateColorPreview(select) {
    var selectedOption = select.options[select.selectedIndex];
    var colorHex = selectedOption.getAttribute('data-hex');
    document.getElementById('colorPreview').style.backgroundColor = colorHex;
}

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

