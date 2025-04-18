<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\ReportRepositoryInterface;
use App\Interfaces\ReportCategoryRepositoryInterface;

class HomeController extends Controller
{
    private ReportCategoryRepositoryInterface $reportCategoryRepository;
    private ReportRepositoryInterface $reportRepository;

    public function __construct(ReportCategoryRepositoryInterface $reportCategoryRepository,
                                ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->reportCategoryRepository = $reportCategoryRepository;
    }

    public function index()
    {
        $categories = $this->reportCategoryRepository->getAllReportCategories();

        $reports = $this->reportRepository->getLatestReports();


        return view('pages.app.home', compact('categories', 'reports'));
    }


}
