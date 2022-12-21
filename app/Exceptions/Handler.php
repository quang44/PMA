<?php

namespace App\Exceptions;

use App\Services\Extend\TelegramService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        if ($request->is('api/*')) {
            if($e instanceof AuthenticationException){
                return response()->json([
                    'message' => 'Mã token không đúng',
                    'result' => false
                ], 401);
            }
//               TelegramService::sendMessage('Có lỗi hệ thống. Message: ' . $e->getMessage() . '. File: ' . $e->getFile() . '. Line: ' . $e->getLine() . url()->full());

            return response()->json([
                'message' => 'Có lỗi hệ thống. Message: ' . $e->getMessage() . '. File: ' . $e->getFile() . '. Line: ' . $e->getLine(),
                'result' => false
            ], 200);

        }
        if($e instanceof NotFoundHttpException || $e instanceof AuthenticationException){

        }else{
//            TelegramService::sendMessage('Có lỗi hệ thống. Message: ' . $e->getMessage() . '. File: ' . $e->getFile() . '. Line: ' . $e->getLine() . url()->full());
        }
        return parent::render($request, $e);
    }
}
