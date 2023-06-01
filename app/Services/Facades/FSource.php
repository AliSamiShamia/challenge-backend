<?php

namespace App\Services\Facades;

use App\Helper\_RuleHelper;
use App\Models\Source;
use App\Services\Interfaces\ISource;
use Illuminate\Http\Request;

class FSource extends FBase implements ISource
{
    public function __construct()
    {
        $this->model = Source::class;
        $this->search = ['title', 'url'];
        $this->rules = [
            'title' => _RuleHelper::_Rule_Require,
            'url' => _RuleHelper::_Rule_Require,
            'api_key' => _RuleHelper::_Rule_Require,
        ];
        $this->columns = ['title', 'url', 'api_key'];
    }


}
