const collectiveInputs = [
    {
        "type": "checkbox",
        "key": "active",
        "label": "Active",
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
    }
    

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
            title: 'Name',
            sortField: 'title',
            dataClass: 'align-middle',
            titleClass: ''
        },
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
    showFilters: false,
    addInputs: collectiveInputs,
}

