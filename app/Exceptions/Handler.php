<?php namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Request;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use League\OAuth2\Server\Exception\AccessDeniedException;
use League\OAuth2\Server\Exception\InvalidRequestException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if (
            $exception instanceof NotFoundHttpException
            && Request::acceptsHtml()
        ) {
            return response(view('index')->render());
        }

        if (
            $exception instanceof AccessDeniedException
            || $exception instanceof InvalidRequestException
        ) {
            return response()->json([], 401);
        }
        else if($exception instanceof FatalErrorException) {
            $message = $exception->getMessage();
            
            if((App::environment() === 'production')) {
                $message = 'An error occured';
            }
            
            return response()->json([
                'error' => $message
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
