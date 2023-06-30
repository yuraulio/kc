const collectiveInputs = [
];

export default {
    fields: [
        // {
        //     name: 'visibility',
        //     title: 'Published' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
        //     dataClass: 'align-middle text-center',
        //     titleClass: 'text-center',
        //     model: 'countdown'
        // },
        {
            name: 'title',
            title: 'Firstname',
            sortField: 'title',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'subtitle',
            title: 'Lastname',
            sortField: 'subtitle',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'header',
            title: 'Header',
            sortField: 'header',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'company',
            title: 'Company',
            sortField: 'company',
            dataClass: 'align-middle',
            titleClass: ''
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
            titleClass: 'text-center'
        },
        {
            name: 'actions',
            title: 'Actions',
            titleClass: 'text-end',
            dataClass: 'align-middle actions-width'
        }
    ],
    apiUrl: '/api/royalties/',
    create: false,
    createLink: '',
    editLink: '/royalties/',
    edit: true,
    editInputs: collectiveInputs,
    loadWidgets: false,
    showFilters: false,
    // perPage: 25,
    filters: [
    ],
    addInputs: collectiveInputs,
}

