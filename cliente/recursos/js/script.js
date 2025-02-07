//DETALLES DEL PRODUCTO

// Incremnetar y decrementar la cantidad
document.addEventListener('DOMContentLoaded', function() {
    const decrementButton = document.getElementById('decrement');
    const incrementButton = document.getElementById('increment');
    const quantityInput = document.getElementById('cantidad');
    let maxQuantity = 0;
    if (quantityInput) {
        maxQuantity = parseInt(quantityInput.max);
    }

    if (decrementButton) {
        decrementButton.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
    }

    if (incrementButton) {
        incrementButton.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < maxQuantity) {
                quantityInput.value = currentValue + 1;
            }
        });
    }

      // Interacción de imágenes
      const mainImage = document.querySelector('.main-image');
      const smallImage = document.querySelector('.small-image');
      const mainImageContainer = document.querySelector('.main-image-container');
  
      if (smallImage) {
          smallImage.addEventListener('mouseover', function() {
              const tempSrc = mainImage.src;
              mainImage.src = smallImage.src;
              smallImage.src = tempSrc;
          });
      }
      if (mainImageContainer) {
      mainImageContainer.addEventListener('mousemove', function(e) {
          const rect = mainImageContainer.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          mainImage.style.transformOrigin = `${x}px ${y}px`;
          mainImage.classList.add('zoom');
      });
    }
      if (mainImageContainer) {
      mainImageContainer.addEventListener('mouseleave', function() {
          mainImage.classList.remove('zoom');
      });
    }
//avanzar y retroceder en la sugerencia de productos

    if (document.querySelector('.swiper-container')) {
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 1, // 1 slide con 2 filas
            spaceBetween: 0,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                     spaceBetween: 0,

                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 0,

                },
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 0,

                },
            },
        });
    }
});

//CARRITO
function addToCart(productId, productName, productPrice, quantity, productImage) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let index = carrito.findIndex(item => item.id === productId);

    if (index === -1) {
        // Si el producto no está en el carrito, lo agregamos
        carrito.push({
            id: productId,
            nombre: productName,
            precio: productPrice,
            cantidad: parseInt(quantity),
            img: productImage // Asegúrate de que la imagen esté disponible
        });
    } else {
        // Si el producto ya está en el carrito, actualizamos la cantidad
        carrito[index].cantidad += parseInt(quantity);
    }

    localStorage.setItem('carrito', JSON.stringify(carrito));
    alert('Producto añadido al carrito');
}

//INDEX
function getSelectedBrand() {
    return document.getElementById('brand-filter').value;
}

function filterByBrand(brand) {
    const category = document.querySelector('.nav-link.active').getAttribute('id').replace('v-pills-', '').replace('-tab', '');
    loadProducts(category, brand);
}

function loadProducts(category, brand = '') {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `cargar-productos.php?category=${category}&brand=${brand}`, true);
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            const productList = document.getElementById(`product-list-${category}`);
            if (productList) {
                productList.innerHTML = response.products;
            // Initialize Swiper
            new Swiper(`#swiper-${category}`, {
                slidesPerView: 4,
                spaceBetween: 0,
                grid: {
                    rows: 2,
                    fill: 'row',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                        grid: {
                            rows: 2,
                        },
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                        grid: {
                            rows: 2,
                        },
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 0,
                        grid: {
                            rows: 2,
                        },
                    },
                }
            });
            }
        }
    };
    xhr.send();
}

// Cargar productos iniciales para la categoría "Todos"
document.addEventListener('DOMContentLoaded', function() {
    loadProducts('home');
});

//buscador
function showSuggestions(term) {
    if (term.length == 0) {
        document.getElementById("suggestions-box").style.display = "none";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/cliente/buscar_productos.php?term=" + term, true);
    xhr.onload = function() {
        if (this.status == 200) {
            const suggestions = JSON.parse(this.responseText);
            let suggestionsHtml = "";
            suggestions.forEach(function(suggestion) {
                suggestionsHtml += `
                    <div class="suggestion-item" onclick="selectSuggestion(${suggestion.proId})">
                        <img src="/paneladministrador/recursos/uploads/producto/${suggestion.proImg}" alt="${suggestion.proNombre}" style="width: 50px; height: 50px; margin-right: 10px; border-radius:50px">
                        <span>${suggestion.proNombre}</span>
                    </div>
                `;
            });
            document.getElementById("suggestions-box").innerHTML = suggestionsHtml;
            document.getElementById("suggestions-box").style.display = "block";
        }
    };
    xhr.send();
}

function selectSuggestion(productId) {
    window.location.href = "/cliente/producto/detalleproducto.php?id=" + productId;
}

document.addEventListener('click', function(event) {
    var isClickInside = document.getElementById('search-input').contains(event.target);
    if (!isClickInside) {
        document.getElementById('suggestions-box').style.display = 'none';
    }
});
//buscador