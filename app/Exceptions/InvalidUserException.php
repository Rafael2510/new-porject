<?php


namespace App\Exceptions;
use Illuminate\Http\Response;


class InvalidUserException extends \Exception
{
    public function render(): Response
    {
        $status = 441;
        $error = "Something is wrong";
        $help = "Contact the sales team to verify";

        return response(["error" => $error, "help" => $help], $status);
    }
}
