<?php

namespace Desoft\DVoyager;

use Desoft\DVoyager\Commands\InstallCommand;
use Desoft\DVoyager\Commands\MinInstallCommand;
use Desoft\DVoyager\Commands\RollbackCommand;
use Desoft\DVoyager\Providers\DVoyagerEventServiceProvider;
use Desoft\DVoyager\Rules\CustomUrlRule;
use Desoft\DVoyager\Rules\DimensionsValidationRule;
use Desoft\DVoyager\Rules\ValidationNameRule;
use Desoft\DVoyager\Rules\ValidationPhoneRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class DVoyagerServiceProvider extends ServiceProvider
{
    public function boot(){

        $this->loadTranslationsFrom(dirname(__DIR__).'/lang', 'dvoyager');
        $this->loadViewsFrom(__DIR__.'/../views', 'dvoyager');
        $this->loadRoutesFrom(__DIR__.'/../routes/dvoyager.php');
        $this->loadHelpers();
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->publishes([
            dirname(__DIR__).'/publishable/config/dvoyager.php' => config_path('dvoyager.php'),
            dirname(__DIR__).'/lang' => $this->app->langPath('vendor/dvoyager'),
            __DIR__.'/../views' => base_path('resources/views/vendor/dvoyager')
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                MinInstallCommand::class,
                RollbackCommand::class
            ]);
        }

        $this->registerValidation();

    }

    public function register(){
        $this->app->register(DVoyagerEventServiceProvider::class);
    }

    private function registerValidation()
    {
        Validator::extend('validation_phone', function($attribute, $value, $parameters, $validator){
            $customRule = null;
            if(count($parameters) == 0)
            {
                $customRule = new ValidationPhoneRule();
            }
            else{
                $customRule = new ValidationPhoneRule($parameters[0], $parameters[1] ?? null);
            }

            $validator->addReplacer('validation_phone', function($message, $attribute, $rule, $parameters) use ($customRule) {
                return $customRule->message();
            });

            return $customRule->passes($attribute, $value);
        });

        Validator::extend('custom_url', function($attribute, $value, $parameters, $validator){
            $customRule = new CustomUrlRule();

            $validator->addReplacer('custom_url', function($message, $attribute, $rule, $parameters) use ($customRule) {
                return $customRule->message();
            });

            return $customRule->passes($attribute, $value);
        });

        Validator::extend('validation_name', function($attribute, $value, $parameters, $validator){
            $customRule = new ValidationNameRule();

            $validator->addReplacer('validation_name', function($message, $attribute, $rule, $parameters) use ($customRule) {
                return $customRule->message();
            });

            return $customRule->passes($attribute, $value);
        });
        Validator::extend('dimensions_validation', function($attribute,
                                                                   $value,
                                                                   $parameters,
                                                                   $validator)
            {
                $dimensions = $parameters;
                $cdv = new DimensionsValidationRule($dimensions[0], $dimensions[1], $value->getClientOriginalName());
                $validator->addReplacer('custom_dimensions_validation',
                    function($message, $attribute, $rule, $parameters) use ($cdv) {
                        return $cdv->message();
                    }
                );
                return $cdv->passes($attribute, $value);
            });
    }

    private function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }
}