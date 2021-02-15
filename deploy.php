<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'Books Library');

// Project repository
set('repository', 'git@github.com:amritms/books-library.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', ['vendor']);

// Writable dirs by web server
add('writable_dirs', ['storage', 'bootstrap/cache']);

// by default release folder name is 1, 2 etc
set('release_name', function () {
    return date('YmdHis');
});
// set default branch
set('branch', 'master');

set('keep_releases', 20);
//set('writable_mode', 'chown');
// Hosts

host('books_library')
    ->stage('staging')
    ->set('deploy_path', '/var/www/books-library')
    ->forwardAgent();

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

task('reload:php-fpm', function () {
    run('sudo systemctl reload php7.4-fpm');
});

task('reload:nginx', function(){
    run('sudo systemctl reload nginx');
});

after('deploy', 'reload:php-fpm');
after('deploy', 'reload:nginx');
