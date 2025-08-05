export default [
  {
    title: 'Employee',
    to: 'employees',
    icon: { icon: 'tabler-users', size: 23 },
    active: 'employee',
  },
  {
    title: 'Customer',
    to: 'customers',
    icon: { icon: 'mdi-account-group', size: 23 },
    active: 'customer',
  },
  {
    title: 'Sale',
    icon: { icon: 'tabler-shopping-cart', size: 23 },
    children: [
      {
        title: 'Sales_department',
        to: 'pos',
        icon: { icon: 'mdi-cash-register', size: 23 },
        active: 'pos',
      },
      {
        title: 'Sale History',
        to: 'sales-history',
        icon: { icon: 'mdi-history', size: 23 },
        active: 'sale_history',
      },
      {
        title: 'Installment',
        to: 'installment',
        icon: { icon: 'mdi-calendar-check ', size: 23 },
        active: 'installment',
      },
    ],
  },
  {
    title: 'Rental',
    icon: { icon: 'mdi-cart-arrow-down', size: 23 },
    children: [
      {
        title: 'Rental_department',
        to: 'rental-history',
        icon: { icon: 'mdi-domain', size: 23 },
        active: 'pos',
      },
      {
        title: 'Rental',
        to: 'rentals',
        icon: { icon: 'mdi-cart-arrow-down', size: 23 },
        active: 'rental',
      },
      {
        title: 'Rental History',
        to: 'rental-history-history',
        icon: { icon: 'mdi-history', size: 23 },
        active: 'rental_history',
      },
    ],
  },

  {
    title: 'Service',
    to: 'services',
    icon: { icon: 'mdi-tools', size: 23 },
    active: 'service',
  },

  {
    title: 'Utility Expenses',
    to: 'utility_expenses',
    icon: { icon: 'mdi-cash-multiple', size: 23 },
    active: 'utility_expenses',
  },

  {
    title: 'Report',
    icon: { icon: 'mdi-file-document-outline', size: 23 },
    children: [
      {
        title: 'Total Sales',
        to: 'reports-totalSaleReport',
        icon: { icon: 'mdi-cart', size: 20 },
      },
      {
        title: 'Total Rental',
        to: 'income-rental-report',
        icon: { icon: 'mdi-hand-coin', size: 20 },
      },
      {
        title: 'Total Install',
        to: 'income-install-report',
        icon: { icon: 'mdi-hand-coin', size: 20 },
      },
      {
        title: 'Total Income',
        to: 'reports-totalIncomeReport',
        icon: { icon: 'mdi-hand-coin', size: 20 },
      },
      {
        title: 'Product Stock',
        to: 'income-stock-report',
        icon: { icon: 'mdi-package-variant', size: 20 },
      },
    ],
  },
  {
    title: 'Stock',
    icon: { icon: 'mdi-warehouse', size: 23 },
    children: [
      {
        title: 'Product',
        to: 'products',
        icon: { icon: 'mdi-package-variant', size: 23 },
        active: 'product',
      },
      {
        title: 'Product Stock',
        to: 'product-stock',
        icon: { icon: 'mdi-cube-outline', size: 23 },
      },

      {
        title: 'Filter Stock',
        to: 'filter-stock',
        icon: { icon: 'mdi-filter', size: 23 },
      },
    ],
  },
  {
    title: 'Settings',
    icon: { icon: 'tabler-settings', size: 23 },
    children: [
      {
        title: 'Preference Settings',
        to: 'settings-account-settings',
        icon: { icon: 'tabler-adjustments', size: 23 },
      },
      {
        title: 'Roles',
        to: 'settings-role-settings',
        icon: { icon: 'tabler-users-group', size: 23 },
        active: 'role',
      },
      {
        title: 'Users',
        to: 'settings-user-settings',
        icon: { icon: 'tabler-user-cog', size: 23 },
        active: 'users',
      },
    ],
  },
  {
    title: 'About',
    icon: { icon: 'tabler-info-circle', size: 23 },
    children: [
      {
        title: 'About Company',
        to: 'about-about-company',
        icon: { icon: 'tabler-building', size: 23 },
      },
      {
        title: 'Partner',
        to: 'about-partner',
        icon: { icon: 'tabler-users-group', size: 23 },
      },
    ],
  },
]
