<?php

namespace App\Core\AbstractClasses;

use App\Core\App;
use App\Core\Interfaces\IController;
use App\Core\Interfaces\IService;
use App\Core\Response;
use Exception;

abstract class BaseController implements IController
{
    protected App $app;

    public function setApp(App $app): void
    {
        $this->app = $app;
    }

    protected function initializeServices(): void
    {
    }

    /**
     * @throws Exception
     */
    protected function getService(string $serviceName): IService
    {
        return $this->app->getService($serviceName);
    }


    protected function jsonResponse(array $data, int $status = 200): Response
    {
        return new Response(json_encode($data), $status);
    }

    protected function errorResponse(string $message, int $status = 500): Response
    {
        return new Response(json_encode(['error' => $message]), $status);
    }

    /**
     * @param string $view
     * @param array $data
     * @return Response
     */
    protected function render(string $view, array $data = []): Response
    {
        ob_start();
        extract($data);
        include __DIR__ . "/App/Templates/{$view}.php";
        $content = ob_get_clean();
        return new Response($content, 200);
    }
}
