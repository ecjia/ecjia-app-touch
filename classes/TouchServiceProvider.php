<?php

namespace Ecjia\App\Touch;

use Royalcms\Component\App\AppServiceProvider;

class TouchServiceProvider extends  AppServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-touch');
    }
    
    public function register()
    {
        
    }
    
    
    
}