<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Interfaces\ReportRepositoryInterface;
use App\Interfaces\ResidentRepositoryInterface;
use RealRashid\SweetAlert\Facades\Alert as Swal;
use App\Interfaces\ReportCategoryRepositoryInterface;

class ReportController extends Controller
{
    private ReportRepositoryInterface $reportRepository;

    private ReportCategoryRepositoryInterface $reportCategoryRepository;

    private ResidentRepositoryInterface $residentRepository;



    public function __construct(ReportRepositoryInterface $reportRepository,
                    ReportCategoryRepositoryInterface $reportCategoryRepository,
                    ResidentRepositoryInterface $residentRepository
    )
    {
        $this->reportRepository = $reportRepository;
        $this->reportCategoryRepository = $reportCategoryRepository;
        $this->residentRepository = $residentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Ambil semua kategori laporan
    $categories = \App\Models\ReportCategory::all();

    // Filter laporan berdasarkan kategori dan rentang tanggal
    $reports = \App\Models\Report::with(['resident.user', 'reportCategory', 'reportStatuses'])
        ->when($request->category, fn($query) =>
            $query->whereHas('reportCategory', fn($q) => $q->where('name', $request->category))
        )
        ->when($request->start_date && $request->end_date, fn($query) =>
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ])
        )
        ->latest()
        ->get();

    // Mengirimkan laporan dan kategori ke view
    return view('pages.admin.report.index', compact('reports', 'categories'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $residents = $this->residentRepository->getAllResidents();
        $categories = $this->reportCategoryRepository->getAllReportCategories();

        return view('pages.admin.report.create', compact('residents', 'categories'));


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        $data = $request->validated();

        $data['code'] = 'BWALAPOR' . mt_rand(100000, 999999);

        $data['image'] = $request->file('image')->store('assets/report/image', 'public');

        $this->reportRepository->createReport($data);

        Swal::toast('Data Kategori Berhasil Ditambahkan', 'success')->timerProgressBar();

        return redirect()->route('admin.report.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = $this->reportRepository->getReportById($id);

        return view('pages.admin.report.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $report = $this->reportRepository->getReportById($id);

        $residents = $this->residentRepository->getAllResidents();
        $categories = $this->reportCategoryRepository->getAllReportCategories();

        return view('pages.admin.report.edit', compact('report', 'residents', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, string $id)
    {
         $data = $request->validated();

        if ($request->image){
            $data['image'] = $request->file('image')->store('assets/report/image', 'public');
        }

        $this->reportRepository->updateReport($id, $data);

         Swal::toast( 'Data Laporan Berhasil Diupdate', 'success')->timerProgressBar();

        return redirect()->route('admin.report.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $this->reportRepository->deteleReport($id);

        Swal::toast( 'Data Laporan Berhasil Dihapus', 'success')->timerProgressBar();

         return redirect()->route('admin.report.index');
    }
}
