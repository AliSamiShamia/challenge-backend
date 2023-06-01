<?php


namespace App\Services\Facades;


use App\Helper\_RuleHelper;
use App\Models\User;
use App\Models\UserCategory;
use App\Models\UserSource;
use App\Services\Interfaces\ISource;
use App\Services\Interfaces\IUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FUser extends FBase implements IUser
{

    private $source;

    /**
     * FUser constructor.
     */
    public function __construct(ISource $source)
    {
        $this->model = User::class;
        $this->search = ['first_name', 'last_name', 'email'];
        $this->slugging = "";
        $this->slug = false;

        $this->hasUnique = true;
        $this->unique = "email";

        $this->encrypt = true;

        $this->verificationEmail = false;
        $this->rules = [
            'first_name' => _RuleHelper::_Rule_Require,
            'last_name' => _RuleHelper::_Rule_Require,
            'email' => _RuleHelper::_Rule_Require . "|" . _RuleHelper::_Rule_Email,
            'password' => _RuleHelper::_Rule_Require,
        ];
        $this->encryptColumn = 'password';
        $this->columns = ['first_name', 'last_name', 'email', 'password'];
        $this->source = $source;
    }

    public function getByEmail($email)
    {
        return User::query()->where([
            'email' => $email
        ])->first();
    }


    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => _RuleHelper::_Rule_Require . "|" . _RuleHelper::_Rule_Email,
                'password' => _RuleHelper::_Rule_Require
            ];
            $request->validate($rules);
            $user = $this->getByEmail($request->input('email'));
            if (!$user) {
                return null;
            }
            $password = $request->input('password');
            if (Hash::check($password, $user->password)) {
                Auth::loginUsingId($user->id);
                $user->update([
                    'last_login' => Carbon::now()
                ]);
                return ($user);
            }
            return null;
        } catch (ValidationException $exception) {
            return ValidationException::withMessages($exception->errors());
        } catch (Exception $exception) {
            return new Exception($exception->getMessage());
        }
    }

    public function sources($sources)
    {
        UserSource::query()->where([
            'user_id' => Auth::guard('api')->id(),
        ])->delete();
        foreach ($sources as $item) {
            $source = $this->source->getById($item);
            if ($source) {
                $check = UserSource::query()->where([
                    'user_id' => Auth::guard('api')->id(),
                    'source_id' => $source->id,
                ])->first();
                if (!$check) {
                    UserSource::query()->create([
                        'user_id' => Auth::guard('api')->id(),
                        'source_id' => $source->id,
                    ]);
                }
            }
        }
    }

    public function categories($categories)
    {
        UserCategory::query()->where([
            'user_id' => Auth::guard('api')->id(),
        ])->delete();
        foreach ($categories as $item) {
            $check = UserCategory::query()->where([
                'user_id' => Auth::guard('api')->id(),
                'category_name' => $item,
            ])->first();
            if (!$check) {
                UserCategory::query()->create([
                    'user_id' => Auth::guard('api')->id(),
                    'category_name' => $item,
                ]);
            }
        }
    }

    public function myCategories()
    {
        return UserCategory::query()->where([
            'user_id' => Auth::guard('api')->id(),
        ])->get()->pluck('category_name')->toArray();
    }

    public function mySources()
    {
        return UserSource::query()->where([
            'user_id' => Auth::guard('api')->id(),
        ])->get()->pluck('source_id')->toArray();
    }

    public function resetPassword(Request $request)
    {
//       reset password

    }


    public function forget(Request $request)
    {
//
    }

    public function checkToken($token)
    {
//
    }

}
