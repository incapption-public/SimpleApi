<?php

namespace Incapption\SimpleRest\Interfaces;

interface iApiController
{
	public function get();
	public function index();
    public function create();
    public function update();
    public function delete();
}