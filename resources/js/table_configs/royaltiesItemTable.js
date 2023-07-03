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
            name: 'instructor',
            title: 'Instructor',
            sortField: 'instructor',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'title',
            title: 'Event',
            sortField: 'title',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'income',
            title: 'Royalties',
            sortField: 'income',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'total_event_minutes',
            title: 'Total Event Minutes',
            sortField: 'total_event_minutes',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'total_instructor_minutes',
            title: 'Total Instructor Minutes',
            sortField: 'total_instructor_minutes',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'percent',
            title: 'Percent',
            sortField: 'percent',
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
        // {
        //     name: 'actions',
        //     title: 'Actions',
        //     titleClass: 'text-end',
        //     dataClass: 'align-middle actions-width'
        // }
    ],
    apiUrl: '/api/royalties',
    create: false,
    createLink: '',
    editLink: '',
    edit: false,
    editInputs: collectiveInputs,
    showFilters: true,
    loadWidgets: false,
    filters: [
        'events',
        'from_date',
        'until_date'
    ],
    export: true,
    // perPage: 25,
    addInputs: collectiveInputs,
}

