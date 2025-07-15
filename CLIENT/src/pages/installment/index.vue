<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import Swal from 'sweetalert2'
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'

// Import images (keep your old images)
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
import u7 from '@/assets/images/productImage/u7.png' // eslint-disable-next-line import/no-unresolved
import defaultImage from '@/assets/images/productImage/// eslint-disable-next-line import/no-unresolveddefault.png'

// Map model names to images
const imageMap = {
  'GP-900': img1,
  'GP-6000B': img2,
  'GP-501': img3,
  'GP-80B': img5,
  'G-6000C': img7,
  'GP-50': img8,
  'GP-700S': c2,
  'GP-900S': c1,
  'GP-80S': c4,
  'GP-500S': c5,
  'G-2000BA': c3,
  'AQF-501': u1,
  'FRO-0110': u2,
  'UNDER-SINK-CASE': u7,
  CABINET: u3,
  MAXTREAM: u4,
}

// Categorize products
function determineCategory(model) {
  if (!model) return 'Other'
  if (model.startsWith('GP-')) return 'Stand'
  if (['GP-700S', 'GP-80S', 'G-2000BA'].includes(model.toUpperCase())) {
    return 'Counter Top'
  }
  if (['AQF-501', 'FRO-0110', 'CABINET', 'MAXTREAM', 'UNDER-SINK-CASE'].includes(model.toUpperCase()))
    return 'Under Sink'

  return 'Other'
}

// Reactive product list and cart
const products = ref([])
const cart = reactive([])
const selectedCategory = ref('All')

// Form data
const form = reactive({
  data: {
    model: null,
    price: 0,
    sub_total: null,
    deposit: null,
    date: new Date().toISOString().slice(0, 10),
    seller: null,
    contract_type: '',
    duration: null,
    warranty: null,
    discount: 0,

    customer_id: null, // for existing customer
    customer: {
      name: '',
      phone: '',
      address: '',
      date: new Date().toISOString().slice(0, 10),
      job: '',
    },
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
        const modelKey = (p.model || '').toUpperCase().replace(/\s+/g, '').replace(/-/g, '')
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
          image: matchedKey ? imageMap[matchedKey] : defaultImage,
        }
      })
    }
  } catch (err) {
    console.error('Fetch failed:', err)
    Swal.fire('Error', 'Failed to load products', 'error')
  }
}

// Fetch existing customers
const customers = ref([])
async function fetchCustomers() {
  try {
    const res = await api.get('/customers')
    if (res.status === 200) {
      customers.value = res.data.data
    }
  } catch (err) {
    console.error('Fetch customers failed:', err)
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
    Swal.fire('អស់ស្តុក', 'ទំនិញអស់ស្តុក', 'warning')

    return
  }
  const existing = cart.find(i => i.id === product.id)
  if (existing) {
    if (existing.quantity < product.stock_quantity) {
      existing.quantity++
    } else {
      Swal.fire('ស្តុកមិនគ្រប់', 'មិនមានក្នុងស្តុកទៀតទេ។', 'info')
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

// Total price
const totalPrice = computed(() => cart.reduce((sum, item) => sum + item.price * item.quantity, 0))

// Auto-calculate sub_total based on price and discount
watch([() => form.data.price, () => form.data.discount], ([price, discount]) => {
  const discountAmount = price * (discount / 100)
  form.data.sub_total = price - discountAmount
})

// Submit form
const onCreate = async () => {
  if (cart.length === 0) {
    Swal.fire('កន្រ្តកមិនមានទំនិញ', 'សូមបញ្ចូលទំនិញចូលក្នុងកន្ត្រក', 'warning')

    return
  }

  const { valid } = await refForm.value?.validate()
  if (!valid) return

  submitting.value = true

  try {
    const payload = {
      ...form.data,
      products: cart.map(item => ({
        product_id: item.id,
        quantity: item.quantity,
        price: item.price,
      })),
    }

    const res = await api.post('/sales', payload)

    if (res.status === 200 || res.data.status === 200) {
      router.push({
        name: 'pos-invoice',
        query: { id: res.data.data.id },
      })
    }
  } catch (err) {
    console.error(err.response?.data || err)
    Swal.fire('Error', err.response?.data?.message || 'Failed to create sale', 'error')
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  fetchProducts()
  fetchCustomers()
})
</script>

<template>
  <VCardItem>
    <VCardTitle class="Sales_department text-primary">
      {{ $t('Sales_department') }}
    </VCardTitle>
    <VForm
      ref="refForm"
      @submit.prevent="onCreate"
    >
      <VRow dense>
        <!-- 🔥 Existing Customer Dropdown -->
        <VCol
          cols="12"
          md="6"
          class="mt-4"
        >
          <VSelect
            v-model="form.data.contract_type"
            :label="$t('Contract Type')"
            :rules="[rules.required]"
            :items="['purchase', 'installment']"
            outlined
            clearable
          />
        </VCol>

        <template v-if="form.data.contract_type">
          <!-- 🔥 New Customer Form if no existing selected -->
          <VCol
            v-if="!form.data.customer_id"
            cols="12"
          >
            <h4 class="mb-2">New Customer Information</h4>
            <VRow dense>
              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="form.data.customer.name"
                  :label="$t('Customer Name')"
                  :rules="[rules.required]"
                  outlined
                />
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="form.data.customer.phone"
                  :label="$t('Phone')"
                  outlined
                />
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="form.data.customer.address"
                  :label="$t('Address')"
                  :rules="[rules.required]"
                  outlined
                />
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="form.data.customer.job"
                  :label="$t('Job')"
                  :rules="[rules.required]"
                  outlined
                />
              </VCol>
            </VRow>
          </VCol>
          <!-- Sale Form Fields -->
          <VCol
            cols="12"
            md="6"
          >
            <VSelect
              v-model="form.data.model"
              :label="$t('Model')"
              :rules="[rules.required]"
              :items="products.map(p => p.model)"
              outlined
              clearable
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <VTextField
              v-model="form.data.price"
              :label="$t('Price')"
              readonly
              outlined
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
            class="mt-4"
          >
            <VSelect
              v-model="form.data.discount"
              :label="$t('Discount')"
              :rules="[rules.required]"
              :items="[0, 5, 10, 20, 30]"
              outlined
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
            class="mt-4"
          >
            <VTextField
              v-model="form.data.sub_total"
              :label="$t('Sub_Total')"
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
              v-model="form.data.deposit"
              :label="$t('Deposit')"
              :rules="[rules.required]"
              type="number"
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
              :rules="[rules.required]"
              :label="$t('Date')"
              type="date"
              outlined
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
            class="mt-4"
          >
            <VSelect
              v-model="form.data.duration"
              :label="$t('Duration')"
              :rules="[rules.required]"
              :items="['6 ខែ', '12 ខែ', '36 ខែ']"
              outlined
            />
          </VCol>

          <VCol
            cols="12"
            md="6"
            class="mt-4"
          >
            <VSelect
              v-model="form.data.seller"
              :label="$t('Seller')"
              :rules="[rules.required]"
              :items="['Vean Vong', 'Dorn Sann', 'Sarun Oueng', 'Chea Selin', 'Phoung Chansophol']"
              outlined
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
            class="mt-4"
          >
            <VSelect
              v-model="form.data.warranty"
              :label="$t('Warranty')"
              :rules="[rules.required]"
              :items="['ជួសជុល ការថែទាំប្រចាំខែ​ឥតគិតថ្លៃ']"
              outlined
              clearable
            />
          </VCol>
        </template>
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
                class="mr-2"
                @click="selectCategory(category)"
              >
                {{ category }}
              </VBtn>
            </div>
            <div class="product-list">
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
                <h4 class="mt-2">
                  {{ product.name }}
                </h4>
                <p class="stock">
                  <span :class="{ 'text-danger': product.out_of_stock }">
                    {{ product.out_of_stock ? 'អស់ស្តុក' : product.stock_quantity }}
                  </span>
                </p>
                <p class="price">{{ $t('Price') }}: ${{ product.price }}</p>
                <VBtn
                  :disabled="product.out_of_stock"
                  color="primary"
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
                    class="cart-item"
                  >
                    <template #prepend>
                      <VListItemTitle>{{ item.name }}</VListItemTitle>
                    </template>
                    <template #append>
                      <div class="item-controls">
                        <div class="qty-price">
                          <input
                            v-model.number="item.quantity"
                            type="number"
                            min="1"
                            :max="item.stock_quantity"
                            class="qty-input"
                            @change="updateQuantity(item)"
                          />
                          ${{ (item.price * item.quantity).toFixed(2) }}
                        </div>
                        <VBtn
                          icon
                          color="red"
                          @click="cart.splice(cart.indexOf(item), 1)"
                        >
                          ❌
                        </VBtn>
                      </div>
                    </template>
                  </VListItem>
                </VList>
                <div class="total-row mt-2">
                  <strong>Total:</strong>
                  <strong>${{ totalPrice.toFixed(2) }}</strong>
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
  </VCardItem>
</template>

<route lang="yaml">
meta:
  title: Installment
  layout: default
  subject: Auth
  active: 'installment'
</route>
