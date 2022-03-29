const collectiveInputs = [
    {
        "type": "text",
        "key": "title",
        "label": "Title",
        "size": "col-lg-12"
    },
    {
        "type": "multidropdown",
        "key": "subcategories",
        "label": "Subcategories",
        "size": "col-lg-12",
        "route": "subcategories",
        "multi": true,
        "taggable": true,
        "fetch": false
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
            title: 'Title',
            sortField: 'title',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'subcategories',
            title: 'Subcategories' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
            formatter (value) {
                var subcategories = "";
                if (value && value != []) {
                    value.forEach(function(subcategory) {
                        subcategories = subcategories + "<span class='badge bg-primary'>" + subcategory.title + "</span> &nbsp";
                    });
                }
                return subcategories;
            },
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'user',
            title: 'User',
            sortField: 'user.firstname',
            formatter (value) {
                if(typeof value.firstname === 'string' && typeof value.lastname === 'string') {
                    return value.firstname.replace(/^\w/, c => c.toUpperCase()) + " " + value.lastname.replace(/^\w/, c => c.toUpperCase());
                } else {
                    return "";
                }
            },
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'pages_count',
            title: 'Pages' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
            dataClass: 'align-middle text-center',
            titleClass: 'text-center'
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
            dataClass: 'align-middle'
        }
    ],
    apiUrl: '/api/categories',
    create: true,
    edit: true,
    editInputs: collectiveInputs,
    showFilters: false,
    perPage: 50,
    addInputs: collectiveInputs.concat([
       
    ])
}

