const collectiveInputs = [
  {
    type: 'text',
    key: 'title',
    label: 'Category group title',
    size: 'col-lg-12',
  },
  {
    type: 'multidropdown',
    key: 'type',
    label: 'Type',
    size: 'col-lg-12',
    route: 'getPageTypes',
    fetch: true,
    multi: false,
  },
  {
    type: 'image',
    key: 'category_image',
    label: 'Image',
    main: true,
  },
  {
    type: 'multidropdown',
    key: 'subcategories',
    label: 'Categories (add categories)',
    size: 'col-lg-12',
    route: 'subcategories',
    multi: true,
    taggable: true,
    fetch: false,
    placeholder: 'Start typing to add category',
  },
];

export default {
  fields: [
    {
      name: '__checkbox',
      titleClass: 'center aligned',
      dataClass: 'align-middle',
    },
    {
      name: 'title',
      title: 'Category group',
      sortField: 'title',
      dataClass: 'align-middle',
      titleClass: '',
    },
    {
      name: 'subcategories',
      title: 'Categories' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
      formatter(value) {
        var subcategories = '';
        if (value && value != []) {
          value.forEach(function (subcategory) {
            subcategories = subcategories + "<span class='badge bg-primary'>" + subcategory.title + '</span> &nbsp';
          });
        }
        return subcategories;
      },
      dataClass: 'align-middle',
      titleClass: '',
    },
    {
      name: 'type',
      title: 'Page type',
      sortField: 'type.title',
      dataClass: 'align-middle',
      titleClass: '',
      formatter(value) {
        if (value) {
          return value.title;
        }
        return '';
      },
    },
    {
      name: 'user',
      title: 'User',
      sortField: 'user.firstname',
      formatter(value) {
        if (typeof value.firstname === 'string' && typeof value.lastname === 'string') {
          return (
            value.firstname.replace(/^\w/, (c) => c.toUpperCase()) +
            ' ' +
            value.lastname.replace(/^\w/, (c) => c.toUpperCase())
          );
        } else {
          return '';
        }
      },
      dataClass: 'align-middle',
      titleClass: '',
    },
    {
      name: 'pages_count',
      title: 'Pages' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
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
      name: 'actions',
      title: 'Actions',
      titleClass: 'text-end',
      dataClass: 'align-middle actions-width',
    },
  ],
  apiUrl: '/api/categories',
  create: true,
  edit: true,
  editInputs: collectiveInputs,
  showFilters: false,
  perPage: 50,
  addInputs: collectiveInputs.concat([]),
};
