<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Traits\ApiResponse;
use App\Traits\CrudOperations;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    use ApiResponse ,CrudOperations;

    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', 10);
        $details = License::paginate($pageSize);

        return $this->output(200,('errors.data_retrieved_successfully'), $details);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'code',
            'title',
            'description',
            'issuer_organization_code',
            'issue_duration_days',
            'valid_duration_days',
            'issue_fee',
            'status',
        ]);

        $rules = [
            'code' => 'required|string|max:50|unique:licenses,code',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'issuer_organization_code' => 'required|string|max:50',
            'issue_duration_days' => 'required|integer|min:0',
            'valid_duration_days' => 'required|integer|min:0',
            'issue_fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,archived',
        ];

        $validatedData = $this->validation($data, $rules);
        if (!$validatedData->isSuccessful())
            return $validatedData;

        $license = License::create($data);

        return $this->output(201,('errors.data_added_successfully'), $license);
    }

    public function show(License $license)
    {
        return $this->output(200,('errors.data_restored_successfully'), $license);
    }

    public function update(Request $request, License $license)
    {
        $data = $request->only([
            'code',
            'title',
            'description',
            'issuer_organization_code',
            'issue_duration_days',
            'valid_duration_days',
            'issue_fee',
            'status',
        ]);

        $rules = [
            'code' => 'required|string|max:50|unique:licenses,code,'.$license->id,
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'issuer_organization_code' => 'required|string|max:50',
            'issue_duration_days' => 'required|integer|min:0',
            'valid_duration_days' => 'required|integer|min:0',
            'issue_fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,archived',
        ];

        $validatedData = $this->validation($data, $rules);
        if (!$validatedData->isSuccessful())
            return $validatedData;

        $license->update($data);

        return $this->output(200,('errors.data_updated_successfully'), $license);
    }

     public function softDelete(License $license)
    {
        return $this->softDeleteModel($license);
    }

    public function restore($id)
    {
        return $this->restoreModel($id, License::class);
    }

    public function destroy($id)
    {
        return $this->destroyModel($id, License::class);
    }
}

