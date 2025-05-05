@extends('layouts.admin')

@section('title', 'Data Laporan')

@section('content')
    <!-- Form Filter -->
    <form action="{{ route('admin.report.index') }}" method="GET" class="mb-3">
        <div class="row">
            <!-- Filter Kategori -->
            <div class="col-md-3">
                <label for="category">Kategori Laporan</label>
                <select name="category" id="category" class="form-control">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tanggal -->
            <div class="col-md-3">
                <label for="start_date">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-3">
                <label for="end_date">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <!-- Tombol Filter -->
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary mt-4">Filter</button>
            </div>
        </div>
    </form>

    <!-- Tombol Tambah Data -->
    <a href="{{ route('admin.report.create') }}" class="btn btn-primary mb-3">Tambah Data</a>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Data Laporan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Laporan</th>
                            <th>Pelapor</th>
                            <th>Kategori Laporan</th>
                            <th>Judul Laporan</th>
                            <th>Bukti Laporan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $report->code }}</td>
                                <td>{{ $report->resident->user->name }}</td>
                                <td>{{ $report->reportCategory->name }}</td>
                                <td>{{ $report->title }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $report->image) }}" alt="image" width="100">
                                </td>
                                <td>
                                    <p class="text-secondary date">
                                        {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y H:i') }}
                                    </p>
                                </td>
                                <td>
                                    <a href="{{ route('admin.report.edit', $report->id) }}" class="btn btn-warning">Edit</a>

                                    <a href="{{ route('admin.report.show', $report->id) }}" class="btn btn-info">Show</a>

                                    <form action="{{ route('admin.report.destroy', $report->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
