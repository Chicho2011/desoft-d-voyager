<?php

namespace Desoft\DVoyager\Utils;

class Utilities {
    public static function generateClassPath($name)
    {
        $folderName = 'DVoyager';
        $modelsPath = base_path('app/Models');

        return $modelsPath.'/'.$folderName.'/'.ucfirst($name).'DVoyagerModel.php';
    }

    public static function generateClassNamespace($name)
    {
        $folderName = 'DVoyager';
        $modelsNamespace = 'App\\Models';

        return $modelsNamespace.'\\'.$folderName.'\\'.ucfirst($name).'DVoyagerModel';
    }

    public static function generateBaseControllerNamespace()
    {
        //TODO cambiar todos los folder name por una configuración
        $folderName = 'DVoyager';
        if(file_exists(app_path().'/Http/Controllers/'.$folderName.'/VoyagerBaseController.php'))
        {
            return 'App\\Http\\Controllers\\DVoyager\\VoyagerBaseController';
        }

        return 'Desoft\\DVoyager\\Http\\Controllers\\VoyagerBaseController';
    }
}