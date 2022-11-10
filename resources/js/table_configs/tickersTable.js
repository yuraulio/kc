const collectiveInputs = [
    {
        "type": "toggle",
        "key": "published",
        "label": "Published",
        "size": "col-lg-12"
    },
    {
        "type": "text",
        "key": "title",
        "label": "Title",
        "size": "col-lg-12"
    },
    {
        "type": "text_editor",
        "key": "content",
        "label": "Content",
        "size": "col-lg-12"
    },
    {
        "type": "datepicker",
        "key": "from_date",
        "label": "From",
        "size": "col-lg-12"
    },
    {
        "type": "datepicker",
        "key": "until_date",
        "label": "Until",
        "size": "col-lg-12"
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
            titleClass: 'text-center'
        },
        {
            name: 'title',
            title: 'Name',
            sortField: 'title',
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
    apiUrl: '/api/tickers',
    create: true,
    edit: true,
    editInputs: collectiveInputs,
    loadWidgets: false,
    showFilters: true,
    filters: [
        'visibility',
    ],
    addInputs: collectiveInputs,
}

