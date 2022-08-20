const collectiveInputs = [
    {
        "type": "text",
        "key": "firstname",
        "label": "First Name",
        "size": "col-lg-12"
    },
    {
        "type": "text",
        "key": "lastname",
        "label": "Last Name",
        "size": "col-lg-12"
    },
    {
        "type": "text",
        "key": "email",
        "label": "Email",
        "size": "col-lg-12"
    },
    {
        "type": "text",
        "key": "password",
        "label": "Password",
        "size": "col-lg-12"
    },
    {
        "type": "text",
        "key": "password_confirmation",
        "label": "Confirm Password",
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
            name: 'firstname',
            title: 'First Name',
            sortField: 'firstname',
        },
        {
            name: 'lastname',
            title: 'Last Name',
            sortField: 'lastname',
        },
        {
            name: 'email',
            title: 'Email',
            sortField: 'email',
        },
        {
            name: 'created_at',
            title: 'Created at',
            dataClass: 'text-center',
            titleClass: 'text-center',
            sortField: 'created_at',
        },
        {
            name: 'active',
            title: 'Active',
            dataClass: 'text-center',
            titleClass: 'text-center',
            sortField: 'active',
            formatter (value) {
                if (value) {
                    return '<i class="fa fa-check-circle text-success" aria-hidden="true"></i>';
                }
                return '<i class="fa fa-times-circle text-danger" aria-hidden="true"></i>';
            },
        },
        {
            name: 'actions',
            title: 'Actions',
            titleClass: 'text-end',
        }
    ],
    apiUrl: '/api/users/admins',
    create: true,
    edit: true,
    showFilters: false,
    loadWidgets: false,
    addInputs: collectiveInputs,
    editInputs: collectiveInputs.concat([
        {
            "type": "checkbox",
            "key": "active",
            "label": "Active",
            "size": "col-lg-12"
        }
    ])
}

