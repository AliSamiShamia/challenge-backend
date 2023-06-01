<?php


namespace App\Services\Interfaces;


use Illuminate\Http\Request;

interface IBase
{
    public function index(Request $request);

    public function store(Request $request);

    public function update(Request $request, $id);

    public function delete($id);

    public function getById($id);

    public function getBySlug($slug);

    public function getByColumns($columns);

    public function getByDate(Request $request);

}
