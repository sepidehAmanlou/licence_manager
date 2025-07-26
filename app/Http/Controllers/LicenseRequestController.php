<?php

namespace App\Http\Controllers;

use App\Models\LicenseRequest;
use App\Models\User;
use App\Rules\IranianNationalCode;
use App\Traits\ApiResponse;
use App\Traits\CrudOperations;
use Illuminate\Http\Request;

class LicenseRequestController extends Controller
{
    use ApiResponse ,CrudOperations;

 public function index(Request $request)
    {
        $pageSize = $request->input('page_size',10);
        $details = LicenseRequest::with(['user', 'license'])->paginate($pageSize);
        return $this->output(200,('errors.data_retrieved_successfully'), $details);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'user_id',
            'license_id',
            'business_postal_code',
            'business_address',
        ]);

        $rules = [
            'user_id' => 'required|exists:users,id',
            'license_id' => 'required|exists:licenses,id',
            'business_postal_code' => 'required|string|size:10',
            'business_address' => 'required|string',
        ];

        $validatedData = $this->validation($data, $rules);
        if (!$validatedData->isSuccessful())
            return $validatedData;

        $data['requested_at'] = now();
        $licenseRequest = LicenseRequest::create($data);

        return $this->output(201,('errors.data_added_successfully'), $licenseRequest);
    }

    public function show(LicenseRequest $licenseRequest)
    {   $licenseRequest->load(['user','license']);
        return $this->output(200,('errors.data_retrieved_successfully'), $licenseRequest->toArray());
    }

    public function update(Request $request, LicenseRequest $licenseRequest)
    {
        $data = $request->only([
            'business_postal_code',
            'business_address',
            'status',
            'admin_note',
        ]);

        $rules = [
            'business_postal_code' => 'required|string|size:10',
            'business_address' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'sometimes|nullable|string',
        ];

        $validatedData = $this->validation($data, $rules);
        if (!$validatedData->isSuccessful())
            return $validatedData;
           
            $licenseRequest->load('license');
        if ($data['status'] === 'approved') {
            $data['approved_at'] = now();
            $data['rejected_at'] = null;
            $data['expires_at'] = $licenseRequest->license?->valid_duration_days
        ? $data['approved_at']->copy()->addDays($licenseRequest->license->valid_duration_days): null;
        } elseif ($data['status'] === 'rejected') {
            $data['rejected_at'] = now();
            $data['approved_at'] = null;
            $data['expires_at'] = null;
        } else {
            $data['approved_at'] = null;
            $data['rejected_at'] = null;
            $data['expires_at'] = null;
        }
   
        $licenseRequest->update($data);

        return $this->output(200,('errors.data_updated_successfully'), $licenseRequest);
    }

    public function softDelete(LicenseRequest $licenseRequest)
    {
        return $this->softDeleteModel($licenseRequest);
    }

    public function restore($id)
    {
        return $this->restoreModel($id, LicenseRequest::class);
    }

    public function destroy($id)
    {
        return $this->destroyModel($id, LicenseRequest::class);
    }

// practice 1 part 2

    public function getApprovedRequests(Request $request)
{
    $data = $request->only('national_code');

    $rules = [
        'national_code' => ['required', 'string', 'size:10', new IranianNationalCode()],
    ];

    $validated = $this->validation($data, $rules);
    if (!$validated->isSuccessful()) {
        return $validated;
    }

    $user = User::where('national_code', $data['national_code'])->first();

    if (!$user) {
        return $this->output(404,('errors.user_not_found'));
    }

      $pageSize = $request->input('page_size', 10);
      $requests = LicenseRequest::with('license')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->orderByDesc('requested_at')
            ->paginate($pageSize);

    if ($requests->isEmpty()) {
        return $this->output(400,('errors.no_approved_requests'));
    }
   
    $data = $requests->map(function ($request) {
        return [
            'request_id' => $request->id,
            'request_code'=>$request->request_code,
            'license_code' => $request->license->code,
            'license_title' => $request->license->title,
            'requested_at' => $request->requested_at,
            'requested_at_jalali' => $request->requested_at_jalali,
            'business_postal_code' => $request->business_postal_code,
            'business_address' => $request->business_address,
            'expires_at' => $request->expires_at,
            'expires_at_jalali' => $request->expires_at_jalali,
        ];
    });

    return $this->output(200,( 'errors.data_restored_successfully'), [
            'data' => $data,
            'pagination' => [
            'total' => $requests->total(),
            'per_page' => $requests->perPage(),
            'current_page' => $requests->currentPage(),
            'last_page' => $requests->lastPage(),
        ]
  ]);
    }

}
