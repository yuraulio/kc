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
            name: 'comment',
            title: 'Comment',
            sortField: 'comment',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'page',
            title: 'Page' + '<i title="Column cant be sorted." class="fa fa-info-circle text-muted ms-1"></i>',
            dataClass: 'align-middle',
            titleClass: '',
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
            name: 'user',
            title: 'User',
            sortField: 'user.firstname',
            dataClass: 'align-middle',
            titleClass: ''
        },
        {
            name: 'actions',
            title: 'Actions',
            titleClass: 'text-end',
            dataClass: 'align-middle'
        }
    ],
    apiUrl: '/api/comments',
    create: false,
    edit: false,
    editInputs: collectiveInputs,
    filters: true,
    perPage: 50,
    addInputs: collectiveInputs.concat([
       
    ])
}

