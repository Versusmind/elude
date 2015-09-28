<?php
/**
 * User: LAHAXE Arnaud
 * Date: 25/09/2015
 * Time: 13:50
 * FileName : profiler.php
 * Project : myo2
 */

return [
    'extraDataProviders' => [
        Clockwork\DataSource\ConfigDataSource::class,
        Clockwork\DataSource\EventsDataSource::class,
        Clockwork\DataSource\MemoryDataSource::class,
        Clockwork\DataSource\AuthDataSource::class,
        Clockwork\DataSource\FilesDataSource::class,
        Clockwork\DataSource\CpuDataSource::class,
        Clockwork\DataSource\FrameworkDataSource::class,
    ]
];