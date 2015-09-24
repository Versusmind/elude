<?php namespace App\Libraries\Clockwork;

use Clockwork\DataSource\ExtraDataSourceInterface;
use Clockwork\Request\Request;
use Illuminate\Support\Facades\Config;

class ConfigDataSource implements ExtraDataSourceInterface
{
    /**
     * @return string
     */
    public function getKey()
    {
        return 'configs';
    }

    /**
     * Adds data to the request and returns it
     */
    public function resolve(Request $request)
    {
        $configs = Config::all();

        foreach($configs['database']['connections'] as $type => $connection) {
            if(!isset($connection['password'])) {
                continue;
            }

            $configs['database']['connections'][$type]['password'] = '**********';
        }

        $configs['app']['key'] = '**********';

        return $configs;
    }
}