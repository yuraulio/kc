#!/bin/bash
parent_share_dir="/var/www/{{ ROOT_PROJECT_NAME }}/stage/{{ CI_PROJECT_NAME }}"
app_dir="/var/www/{{ ROOT_PROJECT_NAME }}/{{ CI_JOB_STAGE }}/{{ CI_PROJECT_NAME }}"
app_dir_branch="{{ CI_COMMIT_REF_NAME | lower }}"
branch="{{ CI_COMMIT_REF_NAME | lower | replace('-', '_') }}"
new_release_dir="$app_dir/releases/{{ DEPLOY_TIMESTAMP }}"
app_url="{{ APP_URL | default('app.ansible.default.com') }}"
api_url="{{ API_URL | default('api.ansible.default.com') }}"
project_name="{{ CI_PROJECT_NAME | replace('-', '_') }}"
review_db=$project_name\_$branch

#Set app_dir/branch for review
if [ {{ CI_JOB_STAGE }} == 'review' ];
then
    app_dir="$app_dir/$app_dir_branch";
    new_release_dir="$app_dir/releases/{{ DEPLOY_TIMESTAMP }}"
    [ -d $app_dir/shared ] || cp -r $parent_share_dir/shared $app_dir/shared;
else
    [ -d $app_dir/shared ] || mkdir -p $app_dir/shared
fi

#// if the item passed exists in the shared folder and in the release folder then
#// remove it from the release folder;
#// or if the item passed not existis in the shared folder and existis in the release folder then
#// move it to the shared folder

{% for resource in SHARED_RESOURCES %}
    if ( [ -{{ resource.type }} $app_dir/shared/{{ resource.name }} ] && [ -{{ resource.type }} $new_release_dir/{{ resource.name }} ] );
    then
        rm -rf $new_release_dir/{{ resource.name }};
        echo "rm -rf $new_release_dir/{{ resource.name }}";
    elif ( [ ! -{{ resource.type }} $app_dir/shared/{{ resource.name }} ]  && [ -{{ resource.type }} $new_release_dir/{{ resource.name }} ] );
    then
        mv $new_release_dir/{{ resource.name }} $app_dir/shared/{{ resource.name }};
        echo "mv $new_release_dir/{{ resource.name }} $app_dir/shared/{{ resource.name }}";
    fi
        ln -nfs $app_dir/shared/{{ resource.name }} $new_release_dir/{{ resource.name }}
        echo "Symlink has been set for $new_release_dir/{{ resource.name }}"
{% endfor %}

echo 'Public Storage Symlink'
php $new_release_dir/artisan storage:link

ln -nfs $new_release_dir $app_dir/current
echo "All symlinks have been set"

echo 'Update Index Directory'
sed -i "s@TNT_INDEX_DIRECTORY=.*@TNT_INDEX_DIRECTORY=$app_dir/current/public/storage/tnt-indexes/@" "$app_dir/current/.env"

echo "Clear cache"
php $app_dir/current/artisan cache:clear

echo "Clear config"
php $app_dir/current/artisan config:clear

echo "Clear routes"
php $app_dir/current/artisan route:clear

if [[ {{ CI_PROJECT_NAME }} == *"api"* ]]; then
    echo "Run swagger:generate"
    php $app_dir/current/artisan l5-swagger:generate
fi

#Clone db, init mongo and update env for review
if [ {{ CI_JOB_STAGE }} == 'review' ];
then
    #Edit env vars
    echo "Change env vars for review"
    sed -i "s@APP_ENV=.*@APP_ENV=review@" "$app_dir/current/.env"
    sed -i "s@APP_URL=.*@APP_URL=$app_url@" "$app_dir/current/.env"
    sed -i "s@API_URL=.*@API_URL=$api_url@" "$app_dir/current/.env"
    sed -i "s@QUEUE_CONNECTION=.*@QUEUE_CONNECTION=sync@" "$app_dir/current/.env"
fi

##Run additional commands
source {{ APP_DEPLOY_FOLDER }}/.env
if echo $CICD_COMMANDS|grep -q "migrate"; then
    echo "Do migrations if necessary"
    php $app_dir/current/artisan migrate --force --no-interaction
fi
if echo $CICD_COMMANDS|grep -q "seed"; then
    echo "Run seederes"
    php $app_dir/current/artisan db:seed --force
fi