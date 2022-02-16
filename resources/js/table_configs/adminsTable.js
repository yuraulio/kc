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
    }
];

export default {
    fields: [
        {
            name: 'firstname',
            title: 'First Name',
        },
        {
            name: 'lastname',
            title: 'Last Name',
        },
        {
            name: 'email',
            title: 'Email',
        },
        {
            name: 'created_at',
            title: 'Created at',
            dataClass: 'text-center',
            titleClass: 'text-center',
        },
        {
            name: 'actions',
            title: 'Actions',
            titleClass: 'text-end',
        }
    ],
    apiUrl: '/api/users/admins',
    editInputs: collectiveInputs,
    addInputs: collectiveInputs.concat([
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
        },
    ])
}

