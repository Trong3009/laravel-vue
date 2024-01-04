<?php

namespace App\Repositories\Impl;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class RepositoryException extends Exception
{
    /**
     * @var string
     */
    public $message;

    /**
     * GeneralException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function render(Request $request): RedirectResponse
    {
        /*
         * All instances of GeneralException redirect back with a flash message to show a bootstrap alert-error
         */
        return redirect()
            ->back()
            ->withInput($request->except('password', 'password_confirmation'))
            ->withFlashDanger($this->message);
    }
}
