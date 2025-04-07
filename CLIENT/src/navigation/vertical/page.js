export default [
  {
    title: 'Users',
    to: 'settings-user-settings',
    icon: { icon: 'tabler-user-cog', size: 23 },
    active: 'users',
  },
  {
    title: 'Roles',
    to: 'settings-role-settings',
    icon: { icon: 'tabler-users-group', size: 23 },
    active: 'role',
  },
  {
    title: 'Employee',
    to: 'employees',
    icon: { icon: 'tabler-users', size: 23 },
    active: 'employee',
  },
  {
    title: 'Customer',
    to: 'customers',
    icon: { icon: 'tabler-user', size: 23 },
    active: 'customer',
  },
  {
    title: 'Product',
    to: 'products',
    icon: { icon: 'tabler-package', size: 23 },
    active: 'product',
  },
  {
    title: 'Sale',
    to: 'sales',
    icon: { icon: 'tabler-cash', size: 23 },
    active: 'sale',
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
    title: 'Stock',
    icon: { icon: 'mdi-warehouse', size: 23 },
    children: [
      {
        title: 'Product Stock',
        to: 'product-stock',
        icon: { icon: 'mdi-package-variant-closed', size: 23 },
      },
      {
        title: 'Filter Stock',
        to: 'filter-stock',
        icon: { icon: 'mdi-filter-menu', size: 23 },
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
        icon: { icon: 'tabler-users', size: 23 },
      },
    ],
  },
]
