const collectiveInputs = [];

export default {
  fields: [
    {
      name: '__checkbox',
      titleClass: 'center aligned',
      dataClass: 'align-middle',
    },
    {
      name: 'title',
      title: 'Name',
      sortField: 'title',
      dataClass: 'align-middle',
      titleClass: '',
    },
    {
      name: 'pages',
      title: 'Pages based on it' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
      dataClass: 'align-middle text-center',
      titleClass: 'text-center',
    },
    {
      name: 'created_at',
      title: 'Created at',
      dataClass: 'text-center',
      titleClass: 'text-center',
      sortField: 'created_at',
      dataClass: 'align-middle text-center',
      titleClass: 'text-center',
    },
    {
      name: 'dynamic',
      title: 'Dinamic',
      dataClass: 'text-center align-middle',
      titleClass: 'text-center',
      sortField: 'dynamic',
      formatter(value) {
        if (value) {
          return '<i class="fa fa-check-circle" aria-hidden="true"></i>';
        }
        return '';
      },
    },
    {
      name: 'actions',
      title: 'Actions',
      titleClass: 'text-end',
      dataClass: 'align-middle actions-width',
    },
  ],
  apiUrl: '/api/templates',
  create: true,
  createLink: '/new_template',
  edit: true,
  editLink: '/template/',
  editInputs: collectiveInputs,
  showFilters: true,
  perPage: 50,
  addInputs: collectiveInputs.concat([]),
  filters: ['dynamic'],
};
