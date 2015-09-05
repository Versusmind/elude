<?php namespace App\Http\Controllers\Api;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file Profiler.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Profiler
 *
 ******************************************************************************/

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Monolog\Logger;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;

class Profiler extends Controller
{

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {

        return response()->json($this->getDataFromJson());
    }

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function last()
    {
        $profils = $this->getDataFromJson();
        $last = null;

        foreach ($profils as $profil) {
            if (is_null($last)) {
                $last = $profil;
            } elseif ($profil['timestamp'] > $last['timestamp']) {
                $last = $profil;
            }
        }

        return response()->json($last);
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id)
    {
        $filename = storage_path('clockwork/' . $id . '.json');
        if (!is_file($filename)) {
            return response()->json([], 404);
        }

        $profile = json_decode(file_get_contents($filename));

        if (is_bool($profile)) {
            return response()->json([], 503);
        }

        $profile->id = $id;
        $profile->timestamp = Carbon::createFromTimestamp($profile->time)->timestamp;
        $profile->datetime = Carbon::createFromTimestamp($profile->time)->toIso8601String();
        $profile->duration = floor($profile->responseDuration);
        $profile->nbSqlQueries = count($profile->databaseQueries);

        $start = $profile->timelineData->total->start;

        foreach($profile->timelineData as $key => $item) {
            $profile->timelineData->{$key}->start = floor(($item->start - $start) * 1000);
            $profile->timelineData->{$key}->end = floor(($item->end - $start) * 1000);
            $profile->timelineData->{$key}->duration = floor($item->duration);
        }


        foreach($profile->log as $key => $item) {
            $profile->log[$key]->time = floor(($profile->log[$key]->time - $start) * 1000);
            // log from monolog use number instread of string
            if(is_numeric($profile->log[$key]->level)) {
                $profile->log[$key]->level = Logger::getLevelName($profile->log[$key]->level);
            }

            $profile->log[$key]->level = strtoupper($profile->log[$key]->level);
        }


        return response()->json($profile);
    }

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stats()
    {
        $profiles = $this->getDataFromJson();

        $result = [
            'nbProfile' => count($profiles),
            'nbSqlQueries' => 0,
            'duration' => 0,
            'nbError' => 0,
            'databaseDuration' => 0,
            'nbLogs' => 0
        ];

        if($result['nbProfile'] === 0) {
            return response()->json($result);
        }

        foreach ($profiles as $profile) {
            $result['nbSqlQueries'] += $profile['nbSqlQueries'];
            $result['duration'] += $profile['duration'];
            $result['databaseDuration'] += $profile['databaseDuration'];
            $result['nbLogs'] += $profile['nbLogs'];
            if($profile['responseStatus'] >= 400 && $profile['responseStatus']!= 401) {
                $result['nbError'] ++;
            }
        }

        $result['nbSqlQueries'] = $result['nbSqlQueries'] / $result['nbProfile'];
        $result['duration'] = $result['duration'] / $result['nbProfile'];
        $result['databaseDuration'] = $result['databaseDuration'] / $result['nbProfile'];
        $result['nbLogs'] = $result['nbLogs'] / $result['nbProfile'];

        return response()->json($result);
    }

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return array
     */
    protected function getDataFromJson()
    {
        $results = [];
        $finder = Finder::create();
        $finder->name('*.json')->date('since 3 hours ago')->depth('== 0')->size('<= 30K')->sortByModifiedTime();
        /** @var File $file */
        foreach ($finder->in(storage_path('clockwork')) as $file) {
            $tmp = json_decode($file->getContents());
            if (is_bool($tmp)) {
                continue;
            }

            $results[] = [
                'id' => $file->getBasename('.json'),
                'method' => $tmp->method,
                'responseStatus' => $tmp->responseStatus,
                'datetime' => Carbon::createFromTimestamp($tmp->time)->toIso8601String(),
                'timestamp' => Carbon::createFromTimestamp($tmp->time)->timestamp,
                'uri' => $tmp->uri,
                'duration' => floor($tmp->responseDuration),
                'databaseDuration' => floor($tmp->databaseDuration),
                'nbSqlQueries' => count($tmp->databaseQueries),
                'nbLogs' => count($tmp->log),
            ];

            unset($tmp);
        }

        return array_reverse($results);
    }
}