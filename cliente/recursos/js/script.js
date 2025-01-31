//DETALLES DEL PRODUCTO

// Intercambiar entre colores y dar stilos
function selectColor(colorName, element) {
    document.getElementById('selected-color-name').innerText = colorName;
    var circles = document.getElementsByClassName('color-circle');
    for (var i = 0; i < circles.length; i++) {
        circles[i].style.boxShadow = 'none';
    }
    element.style.boxShadow = '0 0 0 2px black';
}

    // Selecciona el primer color por defecto
document.addEventListener('DOMContentLoaded', function() {
    var firstCircle = document.querySelector('.color-circle');
    if (firstCircle) {
        selectColor(firstCircle.title, firstCircle);
    }
});

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
function addToCart(productId, productName, productPrice, quantity) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let index = carrito.findIndex(item => item.id === productId);

    if (index === -1) {
        // Si el producto no está en el carrito, lo agregamos
        carrito.push({
            id: productId,
            nombre: productName,
            precio: productPrice,
            cantidad: parseInt(quantity),
            img: '<?php echo $product_image; ?>' // Asegúrate de que la imagen esté disponible
        });
    } else {
        // Si el producto ya está en el carrito, actualizamos la cantidad
        carrito[index].cantidad += parseInt(quantity);
    }

    localStorage.setItem('carrito', JSON.stringify(carrito));
    alert('Producto añadido al carrito');
}
