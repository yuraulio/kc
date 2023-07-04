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
            name: 'royalties_title',
            title: 'Lastname',
            sortField: 'subtitle',
            dataClass: 'align-middle',
            titleClass: '',
            formatter (value) {
                var template = "";
                if (value) {

                    template = "<td class='vuetable-td-title align-middle'><a href='/royalties/'>" + value + "</span></td>";
                }
                return template;
            },
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
            name: 'income',
            title: 'Total Royalties',
            dataClass: 'text-center',
            titleClass: 'text-center',
            sortField: 'income',
            dataClass: 'align-middle text-center',
            titleClass: 'text-center'
        },
        // {
        //     name: 'actions',
        //     title: 'Actions',
        //     titleClass: 'text-end',
        //     dataClass: 'align-middle actions-width'
        // }
        {
            name: 'actions_without_delete',
            title: 'Actions',
            titleClass: 'text-end',
            dataClass: 'align-middle actions-width'
        }
    ],
    apiUrl: '/api/royalties',
    loadSelectedFilterUrl: '/api/royalties-settings',
    create: false,
    createLink: '',
    editLink: '/royalties/',
    edit: true,
    editInputs: collectiveInputs,
    loadWidgets: false,
    showFilters: true,
    perPage: 500,
    filters: [
        'from_date',
        'until_date'
    ],
    addInputs: collectiveInputs,
}

