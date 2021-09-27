<?php

namespace Incapption\SimpleApi\Interfaces;

interface iApiController
{
	public function get() : iMethodResult;
	public function index() : iMethodResult;
    public function create() : iMethodResult;
    public function update() : iMethodResult;
    public function delete() : iMethodResult;
}