export default [  
  // {
  //   title: "Settings",
  //   icon: { icon: "tabler-settings" },
  //   permission: "public",
  //   children: [      
    {
      title: "Transactions",
      to: "transactions",
      icon: { icon: "mdi-cash-sync", size: 13 },
      active: 'transaction'
    },
    {
      title: "Patients",
      to: "patients",
      icon: { icon: "mdi-account-injury-outline", size: 15 },
      active: 'patient'
    },
    {
      title: "Employees",
      to: "employees",
      icon: { icon: "mdi-account-tie", size: 15 },
      active: 'employee'
    },
    { heading: 'Settings' },
    {
      title: "Preference Settings",
      to: "settings-account-settings",
      icon: { icon: "mdi-cog", size: 15 },
      active: 'account-settings'
    },
    {
      title: "Users",
      to: "settings-user-settings",
      icon: { icon: "tabler-user-cog", size: 13 },
      active: 'user'
    },
    {
      title: "Roles",
      to: "settings-role-settings",
      icon: { icon: "tabler-users-group", size: 13 },
      active: 'role'
    },        
    {
      title: "Positions",
      to: "settings-position-settings",
      icon: { icon: "tabler-users-group", size: 13 },
      active: 'position'
    },        
    // ],
  // },
];
