<?php

namespace App\Http\Actions;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Session\SessionManager;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

/**
 *
 */
abstract class AbstractAction
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * @var string A general error to be displayed when some unexpected error has occurred
     */
    const MSG_ERROR = 'An unexpected error has occurred, please try again later.';


    /**
     * Construct an abstract action with the most common dependencies
     *
     * @param SessionManager $session
     * @param AuthManager $auth
     * @param Gate $gate
     * @param ViewFactory $view_factory
     * @param ResponseFactory $response_factory
     * @param Request $request
     * @param UrlGenerator $url
     * @return void
     */
    public function __construct(SessionManager $session, AuthManager $auth, Gate $gate, ViewFactory $view_factory, ResponseFactory $response_factory, Request $request, UrlGenerator $url)
    {
        $this->auth            = $auth;
        $this->gate            = $gate;
        $this->viewFactory     = $view_factory;
        $this->responseFactory = $response_factory;
        $this->session         = $session;
        $this->request         = $request;
        $this->url             = $url;
    }


    /**
     * Create a redirect response with common parameters
     *
     * @param string $location The location to redirect to, should be compatible with route()
     * @param int $status The status code to respond with (default 303)
     * @param array $params Additional query parameters to add to the redirect
     * @return Response A response object
     */
    public function redirect($location, $status = 303, $params = array())
    {
        if ($location instanceof UrlGenerator) {
            $location = $location->full();
        } else {
            $location = $this->url->route($location, $params, NULL);
        }

        return $this->respond(NULL, $status, ['Location' => $location]);
    }


    /**
     * Create a rendered view response with common parameters
     *
     * @param string $path The view path to render
     * @param int $status The status code to respond with (default 200);
     * @param array $data The data to provide the template
     * @param array $headers Additional headers to provide in the response
     * @return Response A response object containing the rendered view as content
     */
    public function render($path, $status = 200, $data = array(), $headers = array())
    {
        $globals = [
            'url'     => $this->url,
            'request' => $this->request
        ];

        return $this->respond(
            $this->viewFactory->make($path, $data, $globals),
            $status
        );
    }


    /**
     * Create a response object with common paraemters
     *
     * @param string $content The content to provide in the response body
     * @param int $status The status code to respond with (default 200);
     * @param array $headers Additional headers to provide in the response
     * @return Response A response object containing the provided content, and headers
     */
    public function respond($content, $status = 200, $headers = [])
    {
        return $this->responseFactory->make($content, $status, $headers);
    }
}
