<?php


namespace Slait\RestfulApi\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait SearchHelper
{
    private $output, $queryBuilder, $relationshipArray, $searchParam, $perPage, $startAt, $endAt;

    public function __construct($model, array $relationshipArray=[])
    {
        $this->relationshipArray = $relationshipArray;
        $this->queryBuilder = $model::query();
        $this->searchParam = request()->query("search");
        $this->perPage = request()->query("per_page");
        $this->endAt = request()->query("end");
        $this->startAt = request()->query("start");
    }

    public function handle()
    {
        try {
            $this->modelHasRelationship();
            $this->handleTimeStampQuery();
            $this->searchTerms();
            $this->groupedBy();
            $this->handlePagination();

        }catch (\Exception $ex) {
            $this->serverError($ex);
        }
        return $this->handleResponse();
    }

    private function handleTimeStampQuery()
    {
        if ($this->startAt!="null" || $this->endAt!="null" || $this->startAt!=null || $this->endAt!=null) {
            if (isset($this->startAt) && isset($this->endAt)) {
                $start = Carbon::parse($this->startAt);
                $end = Carbon::parse($this->endAt);
                $this->queryBuilder = $this->queryBuilder
                    ->whereBetween('created_at', [$start, $end]);
            }
        }
        return $this;
    }

    private function modelHasRelationship()
    {
        if (count($this->relationshipArray)) {
            $this->queryBuilder = $this->queryBuilder->with($this->relationshipArray);
        }
        return $this;
    }

    private function handlePagination()
    {
        if (isset($this->perPage)) {
            $this->output = $this->queryBuilder->paginate($this->perPage);
        }else
            $this->output = $this->queryBuilder->get();

        return $this;
    }

    public function handleResponse()
    {
        try {
            return response()->json([
                "message" => "query returned successfully",
                "data" => $this->output,
                "success" => true,
            ], 200, [], JSON_INVALID_UTF8_SUBSTITUTE);

        }catch (\Exception $ex) {
           $this->serverError($ex);
        }
    }

    private function searchTerms()
    {
        //
        return $this;
    }

    private function groupedBy()
    {
        $this->queryBuilder = $this->queryBuilder->orderBy("created_at", "DESC");
        return $this;
    }

    private function serverError(\Exception $ex)
    {
        Log::error($ex);
        return response()->json([
            "message" => $ex->getMessage(),
            "errors" => $ex->getTrace(),
            "success" => false,
        ], 500, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
}