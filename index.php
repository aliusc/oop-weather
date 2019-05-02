<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Weather\Controller\StartPage;

$request = Request::createFromGlobals();

$loader = new FilesystemLoader('View', __DIR__ . '/src/Weather');
$twig = new Environment($loader, ['cache' => __DIR__ . '/cache', 'debug' => true]);

$source = $request->query->get('source');
$controller = new StartPage($source);

//switch ($request->getRequestUri()) {
//    case '/week':
//        $renderInfo = $controller->getWeekWeather();
//        break;
//    case '/':
//    default:
//        $renderInfo = $controller->getTodayWeather();
//    break;
//}

switch ($request->query->get('period')) {
    case 'week':
        $renderInfo = $controller->getWeekWeather();
        break;
    case 'day':
    default:
        $renderInfo = $controller->getTodayWeather();
        break;
}
$renderInfo['context']['resources_dir'] = 'src/Weather/Resources';

$content = $twig->render($renderInfo['template'], $renderInfo['context']);

$response = new Response(
    $content,
    Response::HTTP_OK,
    array('content-type' => 'text/html')
);
$response->send();
