<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import Swal from 'sweetalert2'
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'

// Import local images

// eslint-disable-next-line import/no-unresolved
import img1 from '@/assets/images/productImage/s13.png' // eslint-disable-next-line import/no-unresolved
import img2 from '@/assets/images/productImage/s12.png' // eslint-disable-next-line import/no-unresolved
import img3 from '@/assets/images/productImage/s11.png' // eslint-disable-next-line import/no-unresolved
import img4 from '@/assets/images/productImage/s10.png' // eslint-disable-next-line import/no-unresolved
import img5 from '@/assets/images/productImage/s9.png' // eslint-disable-next-line import/no-unresolved
import img6 from '@/assets/images/productImage/s2.png' // eslint-disable-next-line import/no-unresolved
import img7 from '@/assets/images/productImage/s14.png' // eslint-disable-next-line import/no-unresolved
import img8 from '@/assets/images/productImage/s15.png' // eslint-disable-next-line import/no-unresolved
import c1 from '@/assets/images/productImage/c1.png' // eslint-disable-next-line import/no-unresolved
import c2 from '@/assets/images/productImage/c2.png' // eslint-disable-next-line import/no-unresolved
import c3 from '@/assets/images/productImage/c3.png' // eslint-disable-next-line import/no-unresolved
import c4 from '@/assets/images/productImage/c4.png' // eslint-disable-next-line import/no-unresolved
import c5 from '@/assets/images/productImage/c5.png' // eslint-disable-next-line import/no-unresolved
import u1 from '@/assets/images/productImage/u1.png' // eslint-disable-next-line import/no-unresolved
import u2 from '@/assets/images/productImage/u2.png' // eslint-disable-next-line import/no-unresolved
import u3 from '@/assets/images/productImage/u3.png' // eslint-disable-next-line import/no-unresolved
import u4 from '@/assets/images/productImage/u4.png' // eslint-disable-next-line import/no-unresolved
import defaultImage from '@/assets/images/productImage/default.png' // fallback image
import { VField } from 'vuetify/lib/components/index.mjs'

// Map model names to images
const imageMap = {
  'GP-900': img1,
  'GP-6000B': img2,
  'GP-501': img3,
  'GP-900S': c1,
  'GP-80B': img5,
  'G-6000C': img7,
  'GP-50': img8,
  'GP-700S': c2,
  'GP-80S': c4,
  'GP-500S': c5,
  'G-2000BA': c3,
  'AQF-501': u1,
  'FRO-0110': u2,
  CABINET: u3,
  MAXTREAM: u4,
}

// Categorize products
function determineCategory(model) {
  if (!model) return 'Other'
  if (model.startsWith('GP-')) return 'Stand'
  if (model.includes('G-')) return 'Counter Top'
  if (['AQF-501', 'FRO-0110', 'CABINET', 'MAXTREAM'].includes(model.toUpperCase())) return 'Under Sink'

  return 'Other'
}

// Reactive product list
const products = ref([])
const cart = reactive([])
const selectedCategory = ref('All')

const form = reactive({
  data: {
    model: null,
    price: 0,
    date: new Date().toISOString().slice(0, 10),
    seller: null,
    contract_type: 'purchase',
  },
})

const submitting = ref(false)
const refForm = ref()

const rules = {
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
}

// Fetch products and assign images
async function fetchProducts() {
  try {
    const res = await api.get('/products?limit=100')
    if (res.status === 200) {
      const data = res.data.data.data

      products.value = data.map(p => {
        const modelKey = (p.model || '').toUpperCase().replace(/\s+/g, '').replace(/-/g, '') // 🔥 normalize API model

        // 🔥 Fuzzy match: find imageMap key contained in modelKey
        const matchedKey = Object.keys(imageMap).find(key => {
          const normalizedKey = key.toUpperCase().replace(/\s+/g, '').replace(/-/g, '')

          return modelKey.includes(normalizedKey)
        })

        return {
          id: p.id,
          name: p.model,
          model: p.model,
          price: p.price,
          stock_quantity: p.stock_quantity ?? 0,
          out_of_stock: !p.stock_quantity || p.stock_quantity <= 0,
          category: determineCategory(p.model),
          image: matchedKey ? imageMap[matchedKey] : defaultImage, // ✅ fallback
        }
      })
    }
  } catch (err) {
    console.error('Fetch failed:', err)
    Swal.fire('Error', 'Failed to load products', 'error')
  }
}

const filteredProducts = computed(() =>
  selectedCategory.value === 'All' ? products.value : products.value.filter(p => p.category === selectedCategory.value),
)

function selectCategory(category) {
  selectedCategory.value = category
}

function onProductClick(product) {
  if (product.out_of_stock) {
    Swal.fire('Out of Stock', 'This product is out of stock.', 'warning')

    return
  }
  const existing = cart.find(i => i.id === product.id)
  if (existing) {
    if (existing.quantity < product.stock_quantity) {
      existing.quantity++
    } else {
      Swal.fire('Limit reached', 'No more stock available.', 'info')
    }
  } else {
    cart.push({ ...product, quantity: 1 })
    form.data.model = product.model
    form.data.price = product.price
  }
}

function updateQuantity(item) {
  if (item.quantity < 1) item.quantity = 1
  if (item.quantity > item.stock_quantity) {
    Swal.fire('Limit exceeded', 'Quantity exceeds stock.', 'warning')
    item.quantity = item.stock_quantity
  }
}

const totalPrice = computed(() => cart.reduce((sum, item) => sum + item.price * item.quantity, 0))

watch(totalPrice, val => {
  form.data.price = val
})

const onCreate = async () => {
  if (cart.length === 0) {
    Swal.fire('Empty Cart', 'Please add products to the cart.', 'warning')

    return
  }
  const { valid } = await refForm.value?.validate()
  if (!valid) return

  submitting.value = true
  try {
    await api.post('/sales', { ...form.data, cart })
    Swal.fire('Success', 'Sale created successfully!', 'success')
    router.back()
  } catch (err) {
    console.error(err.response?.data || err)
    Swal.fire('Error', err.response?.data?.message || 'Failed to create sale', 'error')
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  fetchProducts()
})

watch(
  () => form.data.model,
  model => {
    const selectedProduct = products.value.find(p => p.model === model)
    if (selectedProduct) {
      form.data.price = selectedProduct.price
    }
  },
)
</script>

<template>
  <AppFormCreateTemplate
    cols="12"
    :title="$t('Create New Sale')"
    :submitting="submitting"
    @submit="onCreate"
  >
    <VForm
      ref="refForm"
      @submit.prevent="onCreate"
    >
      <VRow dense>
        <!-- Sale Form Fields -->
        <VCol
          cols="12"
          md="6"
        >
          <VSelect
            v-model="form.data.model"
            :label="$t('Model')"
            :rules="[rules.required]"
            :items="products.map(p => p.name)"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.data.price"
            :label="$t('Price')"
            :rules="[rules.required]"
            readonly
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.seller"
            :label="$t('Seller')"
            type="text"
            :rules="[rules.required]"
            outlined
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VTextField
            v-model="form.data.date"
            :label="$t('Date')"
            type="date"
            :rules="[rules.required]"
            outlined
          />
        </VCol>

        <!-- POS Section -->
        <VRow>
          <VCol
            cols="12"
            md="8"
          >
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

            <div class="d-flex flex-wrap">
              <div
                v-for="product in filteredProducts"
                :key="product.id"
                class="product-card"
              >
                <img
                  :src="product.image"
                  :alt="product.name"
                  class="product-image"
                />
                <h4 class="mt-2">{{ product.name }}</h4>
                <p>
                  <span :class="{ 'text-danger': product.out_of_stock }">
                    {{ product.out_of_stock ? 'អស់ស្តុក' : product.stock_quantity }}
                  </span>
                </p>
                <p>Price: ${{ product.price }}</p>
                <VBtn
                  :disabled="product.out_of_stock"
                  color="primary"
                  class="btn_add_to_cart"
                  block
                  @click="onProductClick(product)"
                >
                  ដាក់ចូលកន្ត្រក
                </VBtn>
              </div>
            </div>
          </VCol>

          <!-- Cart -->
          <VCol
            cols="12"
            md="4"
          >
            <div class="cart-summary">
              <h3>🛒 កន្ត្រកទំនិញ</h3>
              <div
                v-if="cart.length === 0"
                class="empty-cart"
              >
                មិនមានទំនិញនៅក្នុងកន្ត្រកទេ
              </div>
              <div v-else>
                <VList>
                  <VListItem
                    v-for="item in cart"
                    :key="item.id"
                  >
                    <VListItemTitle>{{ item.name }}</VListItemTitle>
                    <VListItemSubtitle>
                      Qty:
                      <input
                        v-model.number="item.quantity"
                        type="number"
                        min="1"
                        :max="item.stock_quantity"
                        class="qty-input"
                        @change="updateQuantity(item)"
                      />
                      — ${{ (item.price * item.quantity).toFixed(2) }}
                    </VListItemSubtitle>
                    <VBtn
                      icon
                      color="red"
                      @click="cart.splice(cart.indexOf(item), 1)"
                    >
                      ❌
                    </VBtn>
                  </VListItem>
                </VList>
                <div class="text-right mt-2">
                  <strong>Total: ${{ totalPrice.toFixed(2) }}</strong>
                </div>
                <VBtn
                  block
                  color="primary"
                  class="mt-3"
                  :loading="submitting"
                  :disabled="cart.length === 0"
                  @click="onCreate"
                >
                  ទូរទាត់ប្រាក់
                </VBtn>
              </div>
            </div>
          </VCol>
        </VRow>
      </VRow>
    </VForm>
  </AppFormCreateTemplate>
</template>

<style scoped>
.text-danger {
  color: rgb(177, 6, 6);
  margin-top: 2px;
  font-size: 11px;
}

.menu {
  margin-bottom: 20px;
}
.menu button {
  margin-right: 8px;
}
.product-card {
  width: 150px;
  margin: 5px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background: #fff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
}
.product-image {
  width: 100%;
  height: 100px;
  object-fit: cover;
  border-radius: 4px;
}
.btn_add_to_cart {
  font-size: 13px;
}
.cart-summary {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  position: sticky;
  top: 20px;
  max-height: calc(100vh - 40px);
  overflow-y: auto;
}
.qty-input {
  width: 60px;
  margin-left: 5px;
}
.empty-cart {
  text-align: center;
  color: #999;
}
</style>

<route lang="yaml">
meta:
  title: Sale Create POS
  layout: default
  subject: Auth
  active: 'pos'
</route>
