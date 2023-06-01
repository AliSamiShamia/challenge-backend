<?php


namespace App\Services\Facades;

use App\Helper\_EmailHelper;
use App\Services\Interfaces\IBase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Nette\Schema\ValidationException;

class FBase implements IBase
{

    protected $model,
        $table,
        $columns,
        $rules,
        $code = false,
        $codeColumn,
        $search, //search columns
        $slug, //to compare column if it`s already exist or not
        $slugging, //this is the column will use it to extract the slug
        $private, //Use it to check the permission, as we will use it to check whether the user has permission to access this records or not
        $privateInstance, //the table will use it to check the permission
        $privateColumn, //the column will use it to check the permission
        $selectedColumn, //the column will get it after check the permission
        $privateId, //the id for the users
        $trackExist, //check if auth id will store it in table
        $trackId, //the auth id
        $trackColumn, //the auth column in track table
        $encrypt = false, // to check if this table has encrypt column
        $encryptColumn, //name of the encrypt column
        $unique,
        $hasUnique,
        $verificationEmail, //to check if this model can send verification email or not
        $orderBy = "asc",
        $columnOrdering = "created_at",
        $dateColumns;


    public function __instance()
    {
        return new $this->model;
    }

    public function _instancePrivate()
    {
        return new $this->privateInstance;
    }

    public function validation(Request $request)
    {
        try {
            $request->validate($this->rules);
            return true;
        } catch (ValidationException $exception) {
            return $exception;
        }
    }

    public function getColumn(Request $request)
    {
        $columns = [];
        if ($this->slug) {
            $slug = Str::slug($request->input($this->slugging));
            $columns = [$this->unique => $slug];
        }
        $all = $request->all();
        foreach ($all as $key => $item) {
            if (($k = array_search($key, $this->columns)) !== false) {
                $columns = array_merge($columns, [$key => $item]);
            }
        }

        if ($this->encrypt) {
            $columns[$this->encryptColumn] = Hash::make($columns[$this->encryptColumn]);
        }
        if ($this->trackExist) {
            $columns[$this->trackColumn] = $this->trackId;
        }

        return $columns;
    }

    public function index(Request $request)
    {
        $temp = $this->__instance()->query();
        if ($request->has('q')) {
            $queryList = explode(' ', $request->input('q'));
            foreach ($queryList as $queryItem) {
                foreach ($this->search as $item) {
                    $temp = $temp->where($item, 'like', '%' . $queryItem . '%');
                }
            }
        }
        if ($this->private) {
            $user = Auth::guard('api')->user();
            if ($user) {
                if ($user->role == 'user') {
                    $temp = $temp->whereIn('id', $this->_instancePrivate()->query()->where($this->privateColumn, $this->privateId)->select($this->selectedColumn)->pluck('event_id')->toArray());
                }
            } else {
                $temp = $temp->whereIn('id', $this->_instancePrivate()->query()->where($this->privateColumn, $this->privateId)->select($this->selectedColumn)->pluck('event_id')->toArray());
            }
        }

        return $temp->orderBy($this->columnOrdering, $this->orderBy)->get();
    }

    public function getByDate(Request $request)
    {
        $temp = $this->__instance()->query();
        if ($request->has('q')) {
            $queryList = explode(' ', $request->input('q'));
            foreach ($queryList as $queryItem) {
                foreach ($this->search as $item) {
                    $temp = $temp->where($item, 'like', '%' . $queryItem . '%');
                }
            }
        }
        if ($request->has('queryDate')) {
            $queryDate = $request->input('queryDate');
            $start_date = Carbon::createFromFormat('Y/m/d H:i:s', $queryDate . "00:00:00");
            $end_date = Carbon::createFromFormat('Y/m/d H:i:s', $queryDate . "23:59:59");
            if ($this->dateColumns) {
                foreach ($this->dateColumns as $dateColumn) {
                    $temp = $temp->whereDate($dateColumn, '>=', $start_date)->whereDate($dateColumn, '<=', $end_date);
                }
            }
        }
        if ($request->has('startDate')) {
            $queryDate = $request->input('startDate');
            $start_date = Carbon::createFromFormat('Y/m/d H:i:s', $queryDate . "00:00:00");
            if ($this->dateColumns) {
                foreach ($this->dateColumns as $dateColumn) {
                    $temp = $temp->whereDate($dateColumn, '>=', $start_date);
                }
            }
        }
        if ($request->has('endDate')) {
            $queryDate = $request->input('endDate');
            $end_date = Carbon::createFromFormat('Y/m/d H:i:s', $queryDate . "23:59:59");
            if ($this->dateColumns) {
                foreach ($this->dateColumns as $dateColumn) {
                    $temp = $temp->whereDate($dateColumn, '<=', $end_date);
                }
            }
        }
        if ($this->private) {
            $temp = $temp->whereIn('id', $this->_instancePrivate()->query()->where($this->privateColumn, $this->privateId)->select($this->selectedColumn)->pluck('event_id')->toArray());
        }
        return $temp->get();
    }

    public function store(Request $request)
    {
        $ex = $this->validation($request);
        if (($ex instanceof ValidationException)) {
            throw new ValidationException($ex->getMessage(), $ex->getMessages());
        }
        if (!$this->checkDuplicate($request)) {
            return null;
        }
        $columns = $this->getColumn($request);
        if ($this->code) {
            $columns = [$this->codeColumn => Str::random(10)];
        }
        $model = $this->__instance()->create($columns);
        if ($this->verificationEmail) {
            $email = new _EmailHelper();
            $email->sendVerification($model);
        }
        return $model;
    }

    public function update(Request $request, $id)
    {
        $ex = $this->validation($request);
        if (($ex instanceof ValidationException)) {
            throw new ValidationException($ex->getMessage(), $ex->getMessages());
        }
        $item = $this->getById($id);
        if ($item) {
            if (!$this->checkDuplicate($request, $id)) {
                return null;
            }
            $columns = $this->getColumn($request);
            $item->update($columns);
        }
        return $item;
    }

    public function delete($id)
    {
        return $this->__instance()->query()->where(['id' => $id])->delete();
    }

    public function getByColumns($columns)
    {
        return $this->__instance()->query()->where($columns);
    }

    public function getById($id)
    {
        return $this->__instance()->query()->where(['id' => $id])->first();
    }

    public function getBySlug($slug)
    {
        return $this->__instance()->query()->where(['slug' => $slug])->first();
    }

    public function checkUnique($value, $key, $id = null)
    {
        if ($id) {
            return $this->__instance()->query()->where(
                [$key => $value]
            )->where('id', '!=', $id)->first();
        }
        return $this->__instance()->query()->where([$key => $value])->first();
    }

    public function checkDuplicate(Request $request, $id = null)
    {
        if ($this->hasUnique) {
            $value = "slug";
            switch ($this->unique) {
                case "slug":
                    $value = Str::slug($request->input($this->slugging));
                    break;
                case "email":
                    $value = $request->input($this->unique);
                    break;
                default:
                    break;
            }
            if ($this->checkUnique($value, $this->unique, $id)) {
                return false;
            }
        }
        return true;
    }

}
