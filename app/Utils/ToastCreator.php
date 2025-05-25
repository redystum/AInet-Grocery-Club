<?php

namespace App\Utils;

class ToastCreator
{
    /**
     * Add a success toast message to the session.
     *
     * @param string $message
     * @param string $title
     * @param int $time
     * @return void
     */
    public static function success(string $message, string $title = 'Success', int $time = 5000): void
    {
        session()->flash('toast', [
            'title' => $title,
            'message' => $message,
            'type' => 'success',
            'time' => $time,
        ]);
    }

    /**
     * Add an error toast message to the session.
     *
     * @param string $message
     * @param string $title
     * @param int $time
     * @return void
     */
    public static function error(string $message, string $title = 'Error', int $time = 5000): void
    {
        session()->flash('toast', [
            'title' => $title,
            'message' => $message,
            'type' => 'error',
            'time' => $time,
        ]);
    }

    /**
     * Add a warning toast message to the session.
     *
     * @param string $message
     * @param string $title
     * @param int $time
     * @return void
     */
    public static function warn(string $message, string $title = 'Warning', int $time = 5000): void
    {
        session()->flash('toast', [
            'title' => $title,
            'message' => $message,
            'type' => 'warning',
            'time' => $time,
        ]);
    }

    /**
     * Add an info toast message to the session.
     *
     * @param string $message
     * @param string $title
     * @param int $time
     * @return void
     */
    public static function info(string $message, string $title = 'Info', int $time = 5000): void
    {
        session()->flash('toast', [
            'title' => $title,
            'message' => $message,
            'type' => 'info',
            'time' => $time,
        ]);
    }
}