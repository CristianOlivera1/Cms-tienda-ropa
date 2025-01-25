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
});
//DETALLES DEL PRODUCTO
