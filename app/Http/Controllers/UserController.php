<?php

namespace App\Http\Controllers;

use App\Helper\_MessageHelper;
use App\Helper\_RuleHelper;
use App\Http\Resources\BaseResource;
use App\Http\Resources\SourceResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSourceResource;
use App\Services\Interfaces\ISource;
use App\Services\Interfaces\IUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $user, $source;

    /**
     * @param IUser $user
     */
    public function __construct(IUser $user, ISource $source)
    {
        $this->user = $user;
        $this->source = $source;
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            return UserResource::create($user);
        } else {
            return BaseResource::ok();
        }
    }

    public function login(Request $request)
    {
        $res = $this->user->login($request);
        if ($res) {
            return UserResource::create($res);
        } else {
            return BaseResource::return(_MessageHelper::NotExist);
        }
    }

    public function register(Request $request)
    {
        try {
            $res = $this->user->store($request);
            if ($res) {
                return UserResource::create($res);
            } else {
                return BaseResource::return(_MessageHelper::NotExist);
            }
        } catch (Exception $exception) {
            Log::error($exception);
            return BaseResource::return(_MessageHelper::ErrorInRequest);
        }
    }

    public function preference(Request $request)
    {
        try {

            if ($request->has('sources')) {
                $this->user->sources($request->input('sources'));
            }
            if ($request->has('categories')) {
                $this->user->categories($request->input('categories'));
            }
            $categories = $this->user->myCategories();

            return BaseResource::create([
                "sources" => UserSourceResource::dataCollection($this->source->index($request)),
                "categories" => $categories
            ]);
        } catch (Exception $exception) {
            Log::error($exception);
            return BaseResource::return(_MessageHelper::ErrorInRequest);
        }

    }

    public function myPreference(Request $request)
    {
        $categories = $this->user->myCategories();

        return BaseResource::create([
            "sources" => UserSourceResource::dataCollection($this->source->index($request)),
            "categories" => $categories
        ]);

    }
}
