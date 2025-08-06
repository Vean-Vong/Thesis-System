<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
// eslint-disable-next-line import/extensions, import/no-unresolved
import api from '@/plugins/utilites'
import Swal from 'sweetalert2'
// eslint-disable-next-line import/extensions, import/no-unresolved
import router from '@/router'
const { t } = useI18n()

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
    seller: '',
    contract_type: '',
    duration: '',
    warranty: 'ជួសជុល ការថែទាំប្រចាំខែ​ឥតគិតថ្លៃ',
    discount: null,
    rental_start_date: new Date().toISOString().slice(0, 10),
    customer_id: null,
    customer: {
      name: '',
      phone: '',
      address: '',
      date: new Date().toISOString().slice(0, 10),
      job: '',
    },
  },
})
const loading = ref([])
const submitting = ref(false)
const refForm = ref()

// Computed flags for conditional rendering
const isPurchase = computed(() => form.data.contract_type === 'purchase')
const isInstallment = computed(() => form.data.contract_type === 'installment')

const rules = {
  required: v => !!v || 'តម្រូវឱ្យបំពេញ',
}

// Fetch products and assign images
async function fetchProducts() {
  loading.value = true
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
  } finally {
    loading.value = false
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

function decreaseProductStock(productId, amount = 1) {
  const product = products.value.find(p => p.id === productId)
  if (product) {
    product.stock_quantity -= amount
    if (product.stock_quantity < 0) product.stock_quantity = 0
    product.out_of_stock = product.stock_quantity <= 0
  }
}

function increaseProductStock(productId, amount = 1) {
  const product = products.value.find(p => p.id === productId)
  if (product) {
    product.stock_quantity += amount
    product.out_of_stock = product.stock_quantity <= 0 ? true : false
  }
}

function removeFromCart(item) {
  increaseProductStock(item.id, item.quantity)
  const index = cart.indexOf(item)
  if (index > -1) {
    cart.splice(index, 1)
  }
}

function onProductClick(product) {
  if (product.stock_quantity <= 0) {
    Swal.fire('អស់ស្តុក', 'ទំនិញអស់ស្តុក', 'warning')

    return
  }

  const existing = cart.find(i => i.id === product.id)
  if (existing) {
    if (product.stock_quantity > 0) {
      existing.quantity++
      decreaseProductStock(product.id, 1)
    } else {
      Swal.fire('ស្តុកមិនគ្រប់', 'មិនមានក្នុងស្តុកទៀតទេ។', 'info')
    }
  } else {
    cart.push({ ...product, quantity: 1 })
    decreaseProductStock(product.id, 1)
    form.data.model = product.model
    form.data.price = product.price
  }
}

// You must call updateQuantity with old quantity before the change.
// Example: <input @change="updateQuantity(item, oldQty)" ... />
function updateQuantity(item, oldQuantity) {
  if (item.quantity < 1) {
    item.quantity = 1
  }

  const product = products.value.find(p => p.id === item.id)
  if (!product) return

  const delta = oldQuantity - item.quantity

  if (delta > 0) {
    // Quantity decreased, increase stock
    product.stock_quantity += delta
  } else if (delta < 0) {
    // Quantity increased, decrease stock
    if (product.stock_quantity + delta < 0) {
      Swal.fire('ស្តុកមិនគ្រប់', 'មិនមានក្នុងស្តុកទៀតទេ។', 'info')
      item.quantity = oldQuantity

      return
    }
    product.stock_quantity += delta // delta is negative here
  }
  product.out_of_stock = product.stock_quantity <= 0
}

// Total price
const totalPrice = computed(() => cart.reduce((sum, item) => sum + item.price * item.quantity, 0))

// Auto-calculate sub_total based on price and discount
watch([cart, () => form.data.discount], () => {
  const discountValue = parseFloat(form.data.discount) || 0
  const discountAmount = totalPrice.value * (discountValue / 100)
  form.data.sub_total = totalPrice.value - discountAmount
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

    console.log('Payload to send:', payload)

    const res = await api.post('/sales', payload)

    if ((res.status === 200 || res.data?.status === 200) && res.data?.data?.id) {
      const saleId = res.data.data.id
      router.push({
        name: 'pos-invoice',
        query: { id: saleId },
      })
    } else {
      Swal.fire('Error', 'Sale creation succeeded but response data invalid.', 'error')
      console.error('Unexpected API response data:', res.data)
    }
  } catch (err) {
    console.error('Error in onCreate:', err)
    const message = err.response?.data?.message || err.message || 'Failed to create sale'
    Swal.fire('Error', message, 'error')
  } finally {
    submitting.value = false
  }
}

const previousQuantities = reactive(new Map())

function previousQuantity(item) {
  return previousQuantities.get(item.id) ?? item.quantity
}

watch(
  cart,
  newCart => {
    newCart.forEach(item => {
      previousQuantities.set(item.id, item.quantity)
    })
  },
  { deep: true },
)

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
        <!-- POS Section -->
        <VRow>
          <VCol
            cols="12"
            md="7"
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
            <!-- Loading -->
            <VCard v-if="loading">
              <VCardText>
                <VProgressCircular
                  indeterminate
                  color="primary"
                />
                <span class="ml-3">{{ $t('Loading data...') }}</span>
              </VCardText>
            </VCard>
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
                <p class="price">${{ product.price }}</p>
                <VBtn
                  :disabled="product.out_of_stock"
                  color="primary"
                  block
                  @click="onProductClick(product)"
                >
                  {{ t('Add to Cart') }}
                  <VIcon end>mdi-cart-plus</VIcon>
                </VBtn>
              </div>
            </div>
          </VCol>

          <!-- Cart -->
          <VCol
            cols="12"
            md="5"
          >
            <div class="cart-summary">
              <div class="d-flex">
                <VIcon size="30">mdi-cart</VIcon>
                <h3 class="ps-3">កន្ត្រកទំនិញ</h3>
              </div>
              <div
                v-if="cart.length === 0"
                class="empty-cart"
              >
                <VIcon size="50">mdi-cart-off</VIcon>
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
                      <VListItemTitle
                        ><h3>{{ item.name }}</h3></VListItemTitle
                      >
                    </template>
                    <template #append>
                      <div class="item-controls">
                        <div class="qty-price">
                          <input
                            :id="'qty-' + item.id"
                            v-model.number="item.quantity"
                            type="number"
                            min="1"
                            :max="item.stock_quantity + item.quantity"
                            step="1"
                            class="qty-input"
                            @change="updateQuantity(item, previousQuantity(item))"
                          />
                          <div class="price-display">${{ (item.price * item.quantity).toFixed(2) }}</div>
                        </div>
                        <VBtn
                          icon
                          color="red"
                          @click="removeFromCart(item)"
                        >
                          ❌
                        </VBtn>
                      </div>
                    </template>
                  </VListItem>
                </VList>
                <div class="total-row mt-2">
                  <h4>{{ $t('Sub_Total') }} (Before Discount)</h4>
                  <h4>${{ totalPrice.toFixed(2) }}</h4>
                </div>
                <div class="total-row mt-2">
                  <h4>{{ $t('Deposit') }}</h4>
                  <h4>${{ form.data.deposit || 0 }}</h4>
                </div>
                <div class="total-row mt-2">
                  <h4>{{ $t('Discount') }}</h4>
                  <h4>${{ form.data.discount || 0 }}</h4>
                </div>
                <div class="total-row mt-3">
                  <h4>{{ $t('Grand_Total') }} (After Discount)</h4>
                  <h4>${{ form.data.sub_total?.toFixed(2) || 0 }}</h4>
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
        <!-- 🔥 Existing Customer Dropdown -->
        <VCol
          cols="12"
          md="2"
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
            cols="11"
          >
            <h4 class="mb-7 mt-4 text-center">{{ $t('Please fill information') }}</h4>
            <VRow dense>
              <VCol
                cols="12"
                md="3"
              >
                <VTextField
                  v-model="form.data.customer.name"
                  :label="$t('Customer Name')"
                  :rules="[rules.required]"
                  class="mt-4"
                  outlined
                />
              </VCol>
              <VCol
                cols="12"
                md="3"
              >
                <VTextField
                  v-model="form.data.customer.phone"
                  :label="$t('Phone')"
                  class="mt-4"
                  outlined
                />
              </VCol>
              <VCol
                cols="12"
                md="3"
              >
                <VTextField
                  v-model="form.data.customer.address"
                  :label="$t('Address')"
                  :rules="[rules.required]"
                  class="mt-4"
                  outlined
                />
              </VCol>

              <VCol
                cols="12"
                md="3"
              >
                <VSelect
                  v-model="form.data.model"
                  :label="$t('Model')"
                  :rules="[rules.required]"
                  :items="products.map(p => p.model)"
                  outlined
                  class="mt-4"
                  clearable
                />
              </VCol>
              <VCol
                cols="12"
                md="3"
              >
                <VTextField
                  v-model="totalPrice"
                  :label="$t('Price')"
                  readonly
                  class="mt-4"
                  outlined
                />
              </VCol>
              <VCol
                cols="12"
                md="3"
              >
                <VSelect
                  v-model="form.data.discount"
                  :label="$t('Discount')"
                  :rules="[rules.required]"
                  :items="['0', '5', '10', '20', '30']"
                  class="mt-4"
                  outlined
                />
              </VCol>
              <VCol
                cols="12"
                md="3"
              >
                <VTextField
                  v-model="form.data.sub_total"
                  :label="$t('Sub_Total')"
                  readonly
                  class="mt-4"
                  outlined
                />
              </VCol>
              <VCol
                cols="12"
                md="3"
              >
                <VSelect
                  v-model="form.data.warranty"
                  :label="$t('Warranty')"
                  :rules="[rules.required]"
                  :items="['ជួសជុល ការថែទាំប្រចាំខែ​ឥតគិតថ្លៃ']"
                  outlined
                  clearable
                  class="mt-4"
                />
              </VCol>
              <VCol
                cols="12"
                md="3"
              >
                <VTextField
                  v-if="form.data.contract_type == 'installment'"
                  v-model="form.data.customer.job"
                  :label="$t('Job')"
                  class="mt-4"
                  outlined
                />
              </VCol>

              <VCol
                cols="12"
                md="3"
              >
                <VSelect
                  v-if="form.data.contract_type == 'installment'"
                  v-model="form.data.deposit"
                  :label="$t('Deposit')"
                  :items="['100']"
                  outlined
                  class="mt-4"
                  type="number"
                />
              </VCol>
              <VCol
                cols="12"
                md="3"
              >
                <VSelect
                  v-if="form.data.contract_type == 'installment'"
                  v-model="form.data.duration"
                  :label="$t('Duration')"
                  :items="['0', '6 ខែ', '12 ខែ', '36 ខែ']"
                  class="mt-4"
                  outlined
                />
              </VCol>
            </VRow>
            <VRow dense>
              <VCol
                cols="12"
                md="3"
              >
                <VTextField
                  v-model="form.data.date"
                  :rules="[rules.required]"
                  :label="$t('Date')"
                  type="date"
                  class="mt-4"
                  outlined
                /> </VCol
              ><VCol
                cols="12"
                md="3"
              >
                <VSelect
                  v-model="form.data.seller"
                  :label="$t('Seller')"
                  :rules="[rules.required]"
                  :items="['Vean Vong', 'Dorn Sann', 'Sarun Oueng', 'Chea Selin', 'Phoung Chansophol']"
                  outlined
                  class="mt-4"
                />
              </VCol>
            </VRow>
          </VCol>
        </template>
      </VRow>
    </VForm>
  </VCardItem>
</template>

<route lang="yaml">
meta:
  title: Sale Create POS
  layout: default
  subject: Auth
  active: 'create_sale'
</route>
