<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

class TaskOutputDTO extends AbstractDTO implements interfaceDTO
{

    public function rules(): array {};

    public function messages(): array {};

    public function validator(): Validator {};

    public function validate(): array {};


}