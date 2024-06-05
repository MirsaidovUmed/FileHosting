<?php

namespace App\Controllers;

use App\Core\App;
use App\Services\IService;
use Exception;
abstract class BaseController
{
    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @throws Exception
     */
    protected function getService(string $serviceName): IService
    {
        return $this->app->getService($serviceName);
    }
}