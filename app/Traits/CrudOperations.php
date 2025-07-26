<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

trait CrudOperations
{

    public function softDeleteModel(Model $model): JsonResponse
    {
        $model->delete();

        return $this->output(200, 'errors.data_deleted_successfully');
    }

    public function restoreModel(int $id, string $modelClass): JsonResponse
    {
        $model = $modelClass::withTrashed()->find($id);

        if (!$model) {
            return $this->output(404, 'errors.data_not_found');
        }

        if (!$model->trashed()) {
            return $this->output(400, 'errors.data_not_deleted');
        }

        $model->restore();

        return $this->output(200, 'errors.data_restored_successfully', $model);
    }
    
    public function destroyModel(int $id, string $modelClass): JsonResponse
    {
        $model = $modelClass::withTrashed()->find($id);

        if (!$model) {
            return $this->output(404, 'errors.data_not_found');
        }

        if (!$model->trashed()) {
            return $this->output(400, 'errors.data_is_not_soft_deleted');
        }

        $model->forceDelete();

        return $this->output(200, 'errors.data_force_deleted');
    }
}
