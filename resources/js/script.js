let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const dots = document.querySelectorAll('.carousel-dot');
const totalSlides = slides.length;

function showSlide(n) {
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));

    slides[n].classList.add('active');
    slides[n].style.opacity = '1';
    slides[n].style.zIndex = '10';

    dots[n].classList.add('active');
    dots[n].style.backgroundColor = '#2563eb';
    dots[n].style.transform = 'scale(1.2)';

    // Reset other slides
    slides.forEach((slide, idx) => {
        if (idx !== n) {
            slide.style.opacity = '0';
            slide.style.zIndex = '0';
        }
    });

    dots.forEach((dot, idx) => {
        if (idx !== n) {
            dot.style.backgroundColor = '#d1d5db';
            dot.style.transform = 'scale(1)';
        }
    });
}

function changeSlide(direction) {
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
    showSlide(currentSlide);
}

function goToSlide(n) {
    currentSlide = n;
    showSlide(currentSlide);
}

// Auto-advance carousel every 5 seconds
setInterval(() => {
    changeSlide(1);
}, 5000);


// handling pos interface
let cart = [];

function addToCart(element) {
    const name = element.querySelector('p:first-of-type').textContent;
    const priceText = element.querySelector('.text-blue-600').textContent;
    const price = parseInt(priceText.replace(/[^\d]/g, ''));

    // Check if item already in cart
    const existingItem = cart.find(item => item.name === name);
    if (existingItem) {
        existingItem.qty += 1;
    } else {
        cart.push({ name, price, qty: 1 });
    }

    updateCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

function updateQty(index, change) {
    cart[index].qty += change;
    if (cart[index].qty <= 0) {
        removeFromCart(index);
    } else {
        updateCart();
    }
}

function updateCart() {
    const cartItemsEl = document.getElementById('cartItems');

    if (cart.length === 0) {
        cartItemsEl.innerHTML = '<p class="text-gray-500 text-center text-sm">Keranjang kosong</p>';
    } else {
        cartItemsEl.innerHTML = cart.map((item, index) => `
                    <div class="cart-item">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm truncate">${item.name}</p>
                            <p class="text-blue-600 font-bold text-sm">Rp ${item.price.toLocaleString('id-ID')}</p>
                        </div>
                        <div class="qty-control">
                            <button class="qty-btn" onclick="updateQty(${index}, -1)">−</button>
                            <span class="w-6 text-center font-semibold text-sm">${item.qty}</span>
                            <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                        <button onclick="removeFromCart(${index})" class="text-red-600 hover:text-red-700 font-bold text-sm">×</button>
                    </div>
                `).join('');
    }

    // Calculate totals
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('total').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
}
