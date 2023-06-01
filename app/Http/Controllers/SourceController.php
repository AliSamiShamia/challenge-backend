<?php

namespace App\Http\Controllers;

use App\Http\Resources\SourceResource;
use App\Services\Interfaces\ISource;
use Illuminate\Http\Request;

class SourceController extends Controller
{


    private ISource $source;

    /**
     * @param ISource $source
     */
    public function __construct(ISource $source)
    {
        $this->source = $source;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $res = $this->source->index($request);
        return SourceResource::collection($res);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
