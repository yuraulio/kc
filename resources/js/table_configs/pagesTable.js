const collectiveInputs = [

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
            title: 'Visibility' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
            dataClass: 'align-middle text-center',
            titleClass: 'text-center'
        },
        {
            name: 'meta_image',
            title: 'Thumbnail' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
            dataClass: 'align-middle text-center pt-1 pb-1',
            titleClass: 'text-center',
            formatter (value) {
                return "<img src='" + value + "' style='max-height: 45px;'>";
            },
        },
        {
            name: 'title',
            title: 'Name',
            sortField: 'title',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'template',
            title: 'Template',
            sortField: 'template.title',
            dataClass: 'align-middle',
            titleClass: '',
            formatter (value) {
                var template = "";
                if (value) {
                    template = "<a class='position-absolute' style='display: contents;' href='/template/" + value.id + "'><span class='badge bg-warning'>" + value.title + "</span></a>";
                }
                return template;
            },
        },
        {
            name: 'type',
            title: 'Type',
            sortField: 'type',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'categories',
            title: 'Categories' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
            formatter (value) {
                var categories = "";
                if (value && value != []) {
                    value.forEach(function(category) {
                        categories = categories + "<span class='badge bg-primary'>" + category.title + "</span> &nbsp";
                    });
                }
                return categories;
            },
            dataClass: 'align-middle',
            titleClass: ''
        },
        // {
        //     name: 'subcategories',
        //     title: 'Subcategories' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
        //     formatter (value) {
        //         var subcategories = "";
        //         if (value && value != []) {
        //             value.forEach(function(subcategory) {
        //                 subcategories = subcategories + "<span class='badge bg-primary'>" + subcategory.title + "</span> &nbsp";
        //             });
        //         }
        //         return subcategories;
        //     },
        //     dataClass: 'align-middle',
        //     titleClass: ''
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
    apiUrl: '/api/pages',
    create: true,
    createLink: '/new_page',
    edit: true,
    editLink: '/page/',
    editInputs: collectiveInputs,
    showFilters: true,
    filters: [
        'dynamic',
        'visibility',
        'template',
        'type',
        'category',
        'subcategory'
    ],
    perPage: 50,
    addInputs: collectiveInputs.concat([
       
    ]),
}