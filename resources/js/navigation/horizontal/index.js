export default [
  {
    title: 'Dashboard',
    to: { name: 'dashboard' },
    icon: { icon: 'tabler-smart-home' },
  },
  // {
  //   title: 'Materials',
  //   to: { name: 'materials' },
  //   icon: { icon: 'tabler-box' },
  // },
  {
    title: 'IQC',
    to: { name: 'iqc' },
    icon: { icon: 'tabler-files' },
    children: [
      // { title: 'Local Part Report', to: 'second-page' },
      // { title: 'Import Part Report', to: 'second-page' },
      { title: 'Incoming Part Report', to: 'iqc-incoming-part-report' },
    ],
  },
  {
    title: 'Suppliers',
    to: { name: 'suppliers' },
    icon: { icon: 'tabler-shopping-cart' },
  },
  // {
  //   title: 'QC Handover',
  //   to: { name: 'qc-handovers' },
  //   icon: { icon: 'tabler-checkbox' },
  // },  
]
