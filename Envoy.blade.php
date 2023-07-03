@servers(['dev' => 'ivdev@inventoryx.com'])

@setup
    $repository = 'git@gitlab.wittkopf.eu:olly/recom-inventory-system.git';
    $releases_dir = '/var/www/dev/web/releases';
    $app_dir = '/var/www/dev/web';
    $current_dir = $app_dir . '/com.inventoryx.dev';
    $release = date('YmdHis');
    $branch = 'development';
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    composer_install_dev
    npm_install
    npm_run_dev
    npm_clean
    update_symlinks
    telescope_activate
    migrate
    cache
    cleanup_releases
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --branch {{ $branch }} --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('composer_install_dev')
    echo "composer install dev ({{ $release }})"
    cd {{ $new_release_dir }}
    composer install --prefer-dist --no-scripts --quiet --optimize-autoloader
@endtask

@task('composer_install_prod')
    echo "composer install prod ({{ $release }})"
    cd {{ $new_release_dir }}
    composer install --prefer-dist --no-scripts --quiet --optimize-autoloader --no-dev
@endtask

@task('npm_install')
    echo "npm install ({{ $release }})"
    cd {{ $new_release_dir }}
    npm install --quiet --no-progress
@endtask

@task('npm_clean')
    echo "npm clean ({{ $release }})"
    cd {{ $new_release_dir }}
    rm package-lock.json
    rm -r node_modules
@endtask

@task('telescope_activate')
    echo "telescope activate ({{ $release }})"
    cd {{ $new_release_dir }}
    php artisan telescope:install
@endtask

@task('npm_run_dev')
    echo "npm run dev ({{ $release }})"
    cd {{ $new_release_dir }}
    npm run dev --quiet --no-progress
@endtask

@task('npm_run_prod')
    echo "npm run prod ({{ $release }})"
    cd {{ $new_release_dir }}
    npm run prod --silent --no-progress
@endtask

@task('update_symlinks')
    echo "Create folder"
    [ -d {{ $app_dir }}/storage ] || mkdir {{ $app_dir }}/storage
    [ -d {{ $app_dir }}/storage/framework ] || mkdir {{ $app_dir }}/storage/framework
    [ -d {{ $app_dir }}/storage/framework/sessions ] || mkdir {{ $app_dir }}/storage/framework/sessions
    [ -d {{ $app_dir }}/storage/framework/views ] || mkdir {{ $app_dir }}/storage/framework/views
    [ -d {{ $app_dir }}/storage/framework/cache ] || mkdir {{ $app_dir }}/storage/framework/cache

    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $current_dir }}
@endtask

@task('migrate')
    echo "Migrate db"
    cd {{ $current_dir }}
    php artisan migrate --force {{-- forces migration in production (not asked because run as script) --}}
@endtask

@task('cache')
    echo "Cleaning cache"
    cd {{ $current_dir }}
    php artisan route:cache
    php artisan config:cache
    php artisan view:cache
@endtask

@task('cleanup_releases')
    echo "cleanup releases ({{ $release }})"
    cd {{ $releases_dir }}
    ls -1d 20* | head -n -5 | xargs -d '\n' rm -Rf
@endtask
