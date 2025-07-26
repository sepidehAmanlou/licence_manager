<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\IranianMobile;
use App\Rules\IranianNationalCode;
use App\Traits\ApiResponse;
use App\Traits\CrudOperations;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use ApiResponse , CrudOperations;

    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', 10);
        $details= User::paginate($pageSize);

        return $this->output(200, ('errors.data_retrieved_successfully'), $details);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'national_code',
            'first_name',
            'last_name',
            'father_name',
            'birth_date',
            'gender',
            'mobile',
            'postal_code',
            'address',
        ]);

        $rules = [
            'national_code' => ['required', 'string', 'size:10', 'unique:users,national_code', new IranianNationalCode()],
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:2|max:100',
            'father_name' => 'required|string|min:2|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'mobile' => ['required', 'string', 'size:11', 'unique:users,mobile', new IranianMobile()],
            'postal_code' => 'required|string|size:10',
            'address' => 'required|string',
        ];

        $validatedData = $this->validation($data, $rules);
        if (!$validatedData->isSuccessful())
            return $validatedData;

        $user = User::create($data);

        return $this->output(201, ('errors.data_added_successfully'), $user);
    }

    public function show(User $user)
    {
        return $this->output(200, ('errors.data_restored_successfully'), $user);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->only([
            'national_code',
            'first_name',
            'last_name',
            'father_name',
            'birth_date',
            'gender',
            'mobile',
            'postal_code',
            'address',
        ]);

        $rules = [
            'national_code' => ['required', 'string', 'size:10',Rule::unique('users', 'national_code')->ignore($user->id), new IranianNationalCode()],
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:2|max:100',
            'father_name' => 'required|string|min:2|max:100 ',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'mobile' => ['required', 'string', 'size:11',Rule::unique('users', 'mobile')->ignore($user->id), new IranianMobile()],
            'postal_code' => 'required|string|size:10',
            'address' => 'required|string',
        ];

        $validatedData = $this->validation($data, $rules);
        if (!$validatedData->isSuccessful())
            return $validatedData;

        $user->update($data);

        return $this->output(200, ('errors.data_updated_successfully'), $user);
    }
     
       public function softDelete(User $user)
    {
        return $this->softDeleteModel($user);
    }

    public function restore($id)
    {
        return $this->restoreModel($id, User::class);
    }

    public function destroy($id)
    {
        return $this->destroyModel($id, User::class);
    }

}


