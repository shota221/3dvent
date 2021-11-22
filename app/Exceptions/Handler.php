<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Arr;
use Psr\Log\LoggerInterface;
use App\Http\Response;

use Illuminate\Support\Facades\Mail;

use Monolog\Utils;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // application
        InvalidException::class,
        SuspiciousOperationException::class, // invalid method override対策
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    // 一旦エラーチェックのためあとでけす
    //protected $internalDontReport = [];

    /**
     * Overriding
     * 
     * 
     * @param  Throwable $e [description]
     * @return [type]       [description]
     */
    public function report(\Throwable $e)
    {
        if ($this->shouldntReport($e)) {
            return;
        }

        if (is_callable($reportCallable = [$e, 'report'])) {
            return $this->container->call($reportCallable);
        }

        try {
            $logger = $this->container->make(LoggerInterface::class);
        } catch (\Exception $ex) {
            throw $e;
        }

        $logger->error(
            $e->getMessage(),
            array_merge($this->context(), ['exception' => $e]
        ));

        // メール送信 to 保守陣営

        $type = null;

        $user = null;

        if (! app()->runningInConsole()) { 
            // バッチ処理でない場合
            
            $type = app()->getRouteType();

            if (! app()->isHttpRouteTypeApi()) {
                $user = Auth::id();
            }
        } 

        // $this->sendErrorMail(
        //     config('mailing_list.dev_ops'), 
        //     '【PERSONAL_FORM】エラーが発生しました。',  
        //     $e->getMessage(),
        //     $this->normalizeException($e),
        //     $type,
        //     $user
        // );
    }

    /**
     * Get the default context variables for logging.
     *
     * @return array
     */
    protected function context()
    {
        $context = [];

        // if (app()->isHttpRouteTypeAdmin()) {
        //     $context['admin_user_id'] = Auth::id();
        // } else 
        // if (app()->isHttpRouteTypeApi()){
        //     $context['app_user_id'] = Auth::id();
        // }

        try {
            return array_filter($context);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Overriding
     *
     * @param  \Exception  $e
     * @return \Exception
     */
    protected function prepareException(\Throwable $e)
    {
        // if ($e instanceof ValidationException) { 
        //     $e = new InvalidException('validation.invalid', [], $e);
        // } else 
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException('リソースデータが存在しません。', $e);
        } else
        if ($e instanceof MethodNotAllowedHttpException) {
            $e = new NotFoundHttpException('リクエストメソッド不正です。', $e);
        } else 
        if ($e instanceof AuthorizationException) {
            $e = new AccessDeniedHttpException($e->getMessage(), $e);
        } else
        if ($e instanceof TokenMismatchException) {
            $e = new CsrfTokenMismatchException();
        } else 
        if ($e instanceof SuspiciousOperationException) {
            $e = new NotFoundHttpException('不正なアクセスです。', $e); 
        } 

        return $e;
    }

    /**
     * Overriding
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareResponse($request, \Throwable $e)
    {
        // if ($e instanceof InvalidException) {
        //     // TODO
        // } else 
        // if ($e instanceof HttpNotFoundException) {
        //     return response()->view('common.error', [
        //         'statusCode'    => 404, 
        //         'title'         => 'Data Not Found.',
        //         'message'       => ($e->getMessage() ?? 'ページが存在しません。')
        //     ]);
        // } else
        // if ($e instanceof AccessDeniedHttpException) {
        //     return response()->view('common.error', [
        //         'statusCode'    => 401, 
        //         'title'         => 'This Action is unauthorized',
        //         'message'       => 'アクセス権限がありません。<br />権限を確認後再度アクセスしてください。'
        //     ]);
        // } else
        if ($e instanceof CsrfTokenMismatchException) {
            return $this->csrfTokenMismatched($request);
        }

        return parent::prepareResponse($request, $e);
    }

    /**
     * Overriding
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareJsonResponse($request, \Throwable $e)
    {
        if ($e instanceof InvalidException) {
            return response()->json(new Response\ErrorJsonResult($e->errors), 400);
        } else 
        if ($e instanceof NotFoundHttpException) {
            return response()->noContent(404);
        } else 
        if ($e instanceof AccessDeniedHttpException) {
           return $this->forbidden();
        } else
        if ($e instanceof CsrfTokenMismatchException) {
            return $this->csrfTokenMismatched($request);
        }

        return parent::prepareJsonResponse($request, $e);
    }

    private function forbidden()
    {
        if (app()->isHttpRouteTypeApi()) {
            $messageKey = 'api.forbidden';

            $messageCode = trans_code($messageKey);

            $translated = trans($messageKey);

            $error = new Response\Error(null, new Response\Message($messageCode, $translated));

            $errorObj = new Response\ErrorJsonResult([$error]);

            return response()->json($errorObj, 403);
        } else {
            return response()->noContent(403);
        }
    }

    /**
     * Overriding
     * 
     * @param  [type]                  $request   [description]
     * @param  AuthenticationException $exception [description]
     * @return [type]                             [description]
     */
    protected function unauthenticated($request, AuthenticationException $e)
    {
        if ($request->expectsJson()) {
            $messageKey = 'api.unauthenticated';

            $messageCode = trans_code($messageKey);

            $translated = trans($messageKey);

            $error = new Response\Error(null, new Response\Message($messageCode, $translated));

            $errorObj = new Response\ErrorJsonResult([$error]);

            if ($request->ajax()) {
                return response(['redirectTo' => guess_route_path('auth')], 401);
            }

            return response()->json($errorObj, 401)->header('WWW-Authenticate', 'Unauthenticated');
        } else {
            if ($request->hasUserToken()) {
                // ブラウザ上でのOAUTH認証アウト
                return response()->view('errors.401', [
                    'message' => trans('validation.auth_token_expired'),
                ]);
            }

            return redirect()->guest(guess_route_path('auth'));
        }
    }

    /**
     * CSRF token エラーハンドル
     * 
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    private function csrfTokenMismatched($request)
    {
        if ($request->expectsJson()) {
            return response()->noContent(401);
        } else {
            return response()->view('errors.401', [
                'message' => trans('validation.auth_token_expired'),
            ]);
        }
    }

    /**
     * エラーメール送信
     * 
     * @param  [type] $to         [description]
     * @param  [type] $subject    [description]
     * @param  [type] $message    [description]
     * @param  [type] $stacktrace [description]
     * @param  [type] $type       [description]
     * @param  [type] $user       [description]
     * @return [type]             [description]
     */
    private function sendErrorMail($to, $subject, $message, $stacktrace = null, $type = null, $user = null)
    {
        Mail::send(
            [
                'text' => 'Mail.error_notification'
            ], 
            [
                'content'       => $message,
                'type'          => $type,
                'user'          => $user,
                'stacktrace'    => $stacktrace
            ], 
            function(\Illuminate\Mail\Message $message) use($to, $subject) {
                foreach ($to as $address) {
                    $message->to($address);
                }

                $message->subject($subject);

                $message->from(
                    'alert@' . app()->getTld(), 
                    config('app.name') . ' notification'
                );
            }
        );
    }

    /**
     * エラーメール用にログと同じフォーマットでエクセプションをフォーマット
     * 
     * @see Monolog\Formatter\LineFormatter::normalizeException
     * 
     * @param  [type] $e [description]
     * @return [type]    [description]
     */
    private function normalizeException($e)
    {
        // TODO 2.0 only check for Throwable
        if (!$e instanceof \Exception && !$e instanceof \Throwable) {
            throw new \InvalidArgumentException('Exception/Throwable expected, got '.gettype($e).' / '.Utils::getClass($e));
        }

        $previousText = '';
        
        if ($previous = $e->getPrevious()) {
            do {
                $previousText .= ', '.Utils::getClass($previous).'(code: '.$previous->getCode().'): '.$previous->getMessage().' at '.$previous->getFile().':'.$previous->getLine();
            } while ($previous = $previous->getPrevious());
        }

        $str = '[object] ('.Utils::getClass($e).'(code: '.$e->getCode().'): '.$e->getMessage().' at '.$e->getFile().':'.$e->getLine().$previousText.')';

        $str .= "\n[stacktrace]\n".$e->getTraceAsString()."\n";

        return $str;
    }
}
