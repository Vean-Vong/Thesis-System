<template>
  <div class="pos-container">
    <h1 class="pos-title">ផ្នែកលក់</h1>

    <!-- Categories Menu -->

    <div class="menu mb-4">
      <VBtn
        v-for="category in ['All', 'Stand', 'Counter Top', 'Under Sink']"
        :key="category"
        :color="selectedCategory === category ? 'primary' : 'grey'"
        @click="selectCategory(category)"
        class="mr-2"
      >
        {{ category }}
      </VBtn>
    </div>

    <!-- Main POS Layout -->
    <div class="pos-layout">
      <!-- Product List -->
      <div class="product-list">
        <div
          v-for="product in filteredProducts"
          :key="product.id"
          class="cart"
        >
          <div class="images">
            <img
              :src="product.image"
              :alt="product.name"
            />
          </div>
          <div class="cart-details">
            <h3>{{ product.name }}</h3>
            <p>
              <span
                :style="{
                  color: product.stock_quantity > 0 ? 'green' : 'red',
                  fontWeight: 'bold',
                }"
              >
                {{ product.stock_quantity > 0 ? product.stock_quantity : 'អស់ស្តុក' }}
              </span>
            </p>
            <p>Price: ${{ product.price }}</p>
          </div>
          <button
            class="btn-checkout"
            @click.stop="onProductClick(product)"
            :disabled="product.stock_quantity <= 0"
          >
            ដាក់ចូលកន្ត្រក
          </button>
        </div>
      </div>

      <!-- Cart Summary -->
      <div class="cart-summary">
        <h3>🛒 កន្ត្រកទំនិញ</h3>

        <p v-if="cart.length === 0">No items added yet</p>

        <div
          v-else
          class="cart-item"
        >
          <ul>
            <li
              v-for="item in cart"
              :key="item.id"
            >
              <span>{{ item.name }}</span>
              <span class="Qtt">
                Qtt:
                <input
                  v-model.number="item.quantity"
                  type="number"
                  min="1"
                  :max="item.stock_quantity"
                  @change="updateQuantity(item)"
                />
              </span>
              <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
              <button
                class="btn-remove"
                @click.stop="cart.splice(cart.indexOf(item), 1)"
              >
                ❌
              </button>
            </li>
          </ul>
          <div class="total">
            <span>Sub Total:</span>
            <strong>${{ totalPrice.toFixed(2) }}</strong>
          </div>
        </div>

        <button
          class="btn-pay"
          :disabled="cart.length === 0"
        >
          បង់ប្រាក់
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import Swal from 'sweetalert2'
import { reactive } from 'vue'
const cart = reactive([])

// Selected category for filtering
const selectedCategory = ref('All')

const fetchStockQuantities = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/products')
    if (response.status === 200 && response.data.data) {
      const stockData = response.data.data
      products.forEach(product => {
        const stockItem = stockData.find(p => p.model === product.name)
        product.stock_quantity = stockItem ? stockItem.stock_quantity ?? stockItem.quantity : 0
      })
    }
  } catch (error) {
    console.error('Failed to fetch stock quantities:', error)
  }
}

// Filter products based on selected category
const filteredProducts = computed(() => {
  if (selectedCategory.value === 'All') {
    return products
  }
  // eslint-disable-next-line newline-before-return
  return products.filter(p => p.category === selectedCategory.value)
})

// Handle menu click
function selectCategory(category) {
  selectedCategory.value = category
}

// Add product to cart or increment quantity
function onProductClick(product) {
  if (product.stock_quantity <= 0) {
    Swal.fire({
      icon: 'warning',
      title: 'អស់ស្តុក',
      text: 'ទំនិញនេះអស់ស្តុក។ សូមជ្រើសរើសទំនិញផ្សេងទៀត។',
    })

    return
  }

  const existing = cart.find(item => item.id === product.id)
  if (existing) {
    existing.quantity++
  } else {
    cart.push({ ...product, quantity: 1 })
  }
}

// Update cart quantity with bounds check
function updateQuantity(item) {
  if (item.quantity < 1) item.quantity = 1
}

// Fetch stock on load

onMounted(() => {
  fetchStockQuantities()
})

// Compute total price from cart
const totalPrice = computed(() => cart.reduce((sum, item) => sum + item.price * item.quantity, 0))

// Compute total quantity for display
const totalQuantity = computed(() => cart.reduce((sum, item) => sum + item.quantity, 0))

// Stand
// eslint-disable-next-line import/no-unresolved
import img1 from '@/assets/images/productImage/s13.png'
// eslint-disable-next-line import/no-unresolved
import img2 from '@/assets/images/productImage/s12.png'
// eslint-disable-next-line import/no-unresolved
import img3 from '@/assets/images/productImage/s11.png'
// eslint-disable-next-line import/no-unresolved
import img4 from '@/assets/images/productImage/s10.png'
// eslint-disable-next-line import/no-unresolved
import img5 from '@/assets/images/productImage/s9.png'
// eslint-disable-next-line import/no-unresolved
import img6 from '@/assets/images/productImage/s2.png'
// eslint-disable-next-line import/no-unresolved
import img7 from '@/assets/images/productImage/s14.png' // eslint-disable-next-line import/no-unresolved
import img8 from '@/assets/images/productImage/s15.png'

// Stand
// eslint-disable-next-line import/no-unresolved
import c1 from '@/assets/images/productImage/c1.png'
// eslint-disable-next-line import/no-unresolved
import c2 from '@/assets/images/productImage/c2.png'
// eslint-disable-next-line import/no-unresolved
import c3 from '@/assets/images/productImage/c3.png'
// eslint-disable-next-line import/no-unresolved
import c4 from '@/assets/images/productImage/c4.png' // eslint-disable-next-line import/no-unresolved
import c5 from '@/assets/images/productImage/c5.png'

// Under Sink
// eslint-disable-next-line import/no-unresolved
import u1 from '@/assets/images/productImage/u1.png' // eslint-disable-next-line import/no-unresolved
import u2 from '@/assets/images/productImage/u2.png' // eslint-disable-next-line import/no-unresolved
import u3 from '@/assets/images/productImage/u3.png' // eslint-disable-next-line import/no-unresolved
import u4 from '@/assets/images/productImage/u4.png'

const products = reactive([
  {
    id: 1,
    name: 'GP-900',
    stock: 5,
    price: 490,
    image: img1,
    category: 'Stand',
  },
  {
    id: 2,
    name: 'GP-6000B',
    stock: 3,
    price: 720,
    image: img2,
    category: 'Stand',
  },
  {
    id: 3,
    name: 'GP-501',
    stock: 7,
    price: 545,
    image: img3,
    category: 'Stand',
  },
  {
    id: 4,
    name: 'GP-900',
    stock: 2,
    price: 545,
    image: img4,
    category: 'Stand',
  },
  {
    id: 5,
    name: 'GP-900',
    stock: 2,
    price: 545,
    image: img7,
    category: 'Stand',
  },
  {
    id: 6,
    name: 'GP-80B',
    stock: 9,
    price: 790,
    image: img5,
    category: 'Stand',
  },
  {
    id: 7,
    name: 'GP-80B',
    stock: 9,
    price: 790,
    image: img6,
    category: 'Stand',
  },
  {
    id: 8,
    name: 'GP-80B',
    stock: 9,
    price: 790,
    image: img5,
    category: 'Stand',
  },
  {
    id: 9,
    name: 'GP-80B',
    stock: 9,
    price: 790,
    image: img6,
    category: 'Stand',
  },
  {
    id: 10,
    name: 'GP-80B',
    stock: 9,
    price: 790,
    image: img6,
    category: 'Stand',
  },
  {
    id: 11,
    name: 'GP-900s',
    stock: 9,
    price: 545,
    image: c1,
    category: 'Counter Top',
  },
  {
    id: 12,
    name: 'GP-700s',
    stock: 9,
    price: 305,
    image: c2,
    category: 'Counter Top',
  },
  {
    id: 13,
    name: 'G-2000BA',
    stock: 9,
    price: 720,
    image: c3,
    category: 'Counter Top',
  },
  {
    id: 14,
    name: 'GP-80s',
    stock: 9,
    price: 720,
    image: c4,
    category: 'Counter Top',
  },
  {
    id: 15,
    name: 'GP-500s',
    stock: 9,
    price: 490,
    image: c5,
    category: 'Counter Top',
  },
  {
    id: 16,
    name: '	AQF-501',
    stock: 9,
    price: 445,
    image: u1,
    category: 'Under Sink',
  },
  {
    id: 17,
    name: 'FRO-0110',
    stock: 9,
    price: 785,
    image: u2,
    category: 'Under Sink',
  },
  {
    id: 18,
    name: 'Cabinet',
    stock: 9,
    price: 2500,
    image: u3,
    category: 'Under Sink',
  },
  {
    id: 19,
    name: 'Maxtream',
    stock: 9,
    price: 305,
    image: u4,
    category: 'Under Sink',
  },
])
</script>

<route lang="yaml">
meta:
  title: Pos
  layout: default
  subject: Auth
  active: 'pos'
</route>
