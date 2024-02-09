const collectiveInputs = [
  {
    type: 'text',
    key: 'title',
    label: 'Title',
    size: 'col-lg-12',
  },
  {
    type: 'radio',
    key: 'should_visible',
    label: 'This countdown should be visible in:',
    size: 'col-lg-12',
    options: {
      1: {
        name: 'All courses of this category',
        key: 'category',
      },
      2: {
        name: 'All courses delivered online or in a class',
        key: 'event',
      },
    },
  },
  {
    type: 'text_editor',
    key: 'content',
    label: 'Content',
    size: 'col-lg-12',
  },
  {
    type: 'datetimepicker',
    key: 'countdown_to',
    label: 'Countdown To',
    size: 'col-sm-3 col-lg-12',
  },
  {
    type: 'toggle',
    key: 'button_status',
    label: 'Button Status',
    size: 'col-lg-12',
  },
  {
    type: 'text',
    key: 'button_title',
    label: 'Button Title',
    size: 'col-lg-12',
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
      name: 'visibility',
      title: 'Published' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
      dataClass: 'align-middle text-center',
      titleClass: 'text-center',
      model: 'countdown',
    },
    {
      name: 'title',
      title: 'Name',
      sortField: 'title',
      dataClass: 'align-middle',
      titleClass: '',
    },
    // {
    //     name: 'published',
    //     title: 'Published',
    //     dataClass: 'text-center',
    //     titleClass: 'text-center',
    //     sortField: 'published',
    //     formatter (value) {
    //         if (value) {
    //             return '<i class="fa fa-check-circle text-success" aria-hidden="true"></i>';
    //         }
    //         return '<i class="fa fa-times-circle text-danger" aria-hidden="true"></i>';
    //     },
    // },
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
  apiUrl: '/api/countdown',
  create: true,
  createLink: '/new_countdown',
  editLink: '/countdown/',
  edit: true,
  editInputs: collectiveInputs,
  loadWidgets: false,
  showFilters: false,
  filters: [],
  perPage: 50,
  addInputs: collectiveInputs,
};
