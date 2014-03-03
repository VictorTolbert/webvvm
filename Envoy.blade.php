@servers(['web' => 'demo@asgldlvvm05'])

@task('foo', ['on' => 'web'])
    cd webvvm
    ls -la
    git pull origin master
@endtask