<?php


namespace App\Repositories;



use App\Models\ReportStatus;
use App\Interfaces\ReportStatusRepositoryInterface;


class ReportStatusRepository implements ReportStatusRepositoryInterface

{
    public function getAllReportStatuses()
    {
        return ReportStatus::all();
    }

    public function getReportStatusById(int $id)
    {
        return ReportStatus::where('id', $id)->first();
    }

    public function createReportStatus(array $data)
    {


       return ReportStatus::create($data);

    }

    public function updateReportStatus(int $id, array $data)
    {
        $reportStatus = $this->getReportStatusById($id);

        return $reportStatus->update($data);
    }

    public function deteleReportStatus(int $id)
    {
        $reportStatus = $this->getReportStatusById($id);

        return $reportStatus->delete();
    }
}
