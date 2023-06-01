<?php


namespace App\Services\Interfaces;


use Illuminate\Http\Request;

interface IUser extends IBase
{

    public function login(Request $request);

    public function resetPassword(Request $request);

    public function getByEmail($email);

    public function forget(Request $request);

    public function checkToken($token);

    public function sources($sources);

    public function categories($categories);

    public function myCategories();

    public function mySources();
}
