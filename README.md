# peformance-stats

`performance-stats` passes the performance monitoring information of the `laravel` project to a third party, and currently supports datadog

### Install
To install through Composer, by run the following command:

``` bash
composer require spacebib/peformance-stats
```

The package will automatically register a service provider and alias.

Optionally, publish the package's configuration file by running:

``` bash
php artisan vendor:publish --provider="Spacebib\PeformanceStats\PeformanceStatsServiceProvider"
```

### Storage

* [x] DataDogStorage

### Watcher

* [x] Request

### Datadog Agent Install

https://docs.datadoghq.com/agent/

### Datadog Show

https://user-images.githubusercontent.com/74885727/145976336-a4929faa-0c18-4dba-9929-5b2dd86c755b.mov
