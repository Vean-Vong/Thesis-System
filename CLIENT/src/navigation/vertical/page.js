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
        icon: { icon: 'fa-plus', size: 10 },
        active: 'pos',
      },
      {
        title: 'Sale History',
        to: 'sales-history',
        icon: { icon: 'fa-plus', size: 10 },
        active: 'sale_history',
      },
    ],
  },

  {
    title: 'Rental',
    to: 'rental',
    icon: { icon: 'mdi-cart-arrow-down', size: 23 },
    active: 'rental',
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
        title: 'Daily Report',
        to: 'reports',
        icon: { icon: 'fa-plus', size: 10 },
      },
      {
        title: 'New Setup',
        to: 'new-setup',
        icon: { icon: 'fa-plus', size: 10 },
      },
    ],
  },
  {
    title: 'Stock',
    icon: { icon: 'mdi-warehouse', size: 23 },
    children: [
      {
        title: 'Product Stock',
        to: 'product-stock',
        icon: { icon: 'fa-plus', size: 10 },
      },
      {
        title: 'Product',
        to: 'products',
        icon: { icon: 'fa-plus', size: 10 },
        active: 'product',
      },
      {
        title: 'Filter Stock',
        to: 'filter-stock',
        icon: { icon: 'fa-plus', size: 10 },
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
