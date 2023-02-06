<?php

namespace App\Services;

use Illuminate\Http\Request;
use View;
use Log;

class ViewService
{
    protected string $view;

    public function __construct(string $v)
    {
        $this->view = $v;
    }

    public function view()
    {
        return view($this->view);
    }

    public function renderWithKey(string $key, $statusCode, string $view = '')
    {
        if ($view) {
            return view($view)->with('response', $this->messages($key, '', $statusCode));
        }

        return view($this->view)->with('response', $this->messages($key, '', $statusCode));
    }

    public function renderWithMsg($message = '', $statusCode, string $view = '')
    {
        if ($view) {
            return view($view)->with('response', $this->messages('', $message, $statusCode));
        }

        return view($this->view)->with('response', $this->messages('', $message, $statusCode));
    }

    private function messages(?string $key, ?string $message, $statusCode)
    {
        $messages = [
            'register-success' => 'Account registered successfully.',
            'register-fail' => 'Failed to register account.',
            'register-fail-validation' => 'Failed to register account. Some fields were incorrect.',
            'place-order-success' => 'Placed order successfully.',
            'place-order-fail' => 'Failed to place order. Some fields were incorrect.',
        ];

        $success = true;

        if (is_int($statusCode)) {
            if ($statusCode >= 300) {
                // failure of some kind
                $success = false;
            }
        }
        // } else {
        //     $success = false;
        //     $statusCode = 500;
        //     Log::debug('ViewService.php: Invalid status code passed in.');
        // }

        $str = $statusCode . ': ';

        return [
            'response' => $success ? 'success' : 'failure',
            'message' => $key ? $str . $messages[$key] : $str . $message,
        ];
    }

    public function returnResponseWithMsg($message = '', $statusCode)
    {
        return response()->with('response', $this->messages('', $message, $statusCode));
    }
}
