@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container">
        <h1>Admin Dashboard</h1>

        <form method="GET" action="{{ route('admin') }}" class="sort-form">
            <label for="sort">Sorteer op datum:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="desc" {{ $sort == 'desc' ? 'selected' : '' }}>Nieuwste eerst</option>
                <option value="asc" {{ $sort == 'asc' ? 'selected' : '' }}>Oudste eerst</option>
            </select>
        </form>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Beschrijving</th>
                <th>Email</th>
                <th>Telefoon</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Aangemaakt op</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>{{ $report->description }}</td>
                    <td>{{ $report->email ?? '-' }}</td>
                    <td>{{ $report->phone ?? '-' }}</td>

                    <td>
                        @if($report->photo_path)
                            <a href="{{ asset('storage/' . $report->photo_path) }}" target="_blank">
                                Bekijk foto
                            </a>
                        @else
                            Geen foto
                        @endif
                    </td>

                    <td>
                        <form method="POST" action="{{ route('admin.update', $report->id) }}">
                            @csrf
                            <select name="status" onchange="this.form.submit()">
                                <option value="open" {{ $report->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="working" {{ $report->status == 'working' ? 'selected' : '' }}>Working on it</option>
                                <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </form>
                    </td>

                    <td>{{ $report->created_at->format('d-m-Y H:i') }}</td>

                    <td>
                        <form method="POST" action="{{ route('admin.delete', $report->id) }}" onsubmit="return confirm('Weet je zeker dat je deze melding wilt verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
