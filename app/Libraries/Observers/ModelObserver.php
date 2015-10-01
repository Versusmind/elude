<?php namespace App\Libraries\Observers;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * User: LAHAXE Arnaud
 * Date: 29/09/2015
 * Time: 12:13
 * FileName : Model.php
 * Project : myo2
 */
class ModelObserver
{

    public function saved(Model $model){
        Log::debug('Saved ' . get_class($model) . ' #' . $model->getKey());
    }

    public function updating(Model $model){
        Log::debug('Updating ' . get_class($model) . ' #' . $model->getKey());
    }

    public function updated(Model $model){
        Log::debug('Updated ' . get_class($model) . ' #' . $model->getKey());
    }

    public function creating(Model $model){
        Log::debug('Creating ' . get_class($model) . ' #' . $model->getKey());
    }

    public function created(Model $model){
        Log::debug('Created ' . get_class($model) . ' #' . $model->getKey());
    }

    public function deleting(Model $model){
        Log::debug('Deleting ' . get_class($model) . ' #' . $model->getKey());
    }

    public function deleted(Model $model){
        Log::debug('Deleted ' . get_class($model) . ' #' . $model->getKey());
    }

    public function restoring(Model $model){
        Log::debug('Restoring ' . get_class($model) . ' #' . $model->getKey());
    }

    public function restored(Model $model){
        Log::debug('Restored ' . get_class($model) . ' #' . $model->getKey());
    }
}