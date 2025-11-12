@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container">
        <div class="admin-header">
            <div>
                <h1>Admin Dashboard</h1>
                <p style="color: var(--color-text-secondary); margin-top: 0.5rem;">Beheer alle meldingen</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $reports->where('status', 'open')->count() }}</div>
                    <div class="stat-label">Open</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $reports->where('status', 'working')->count() }}</div>
                    <div class="stat-label">In behandeling</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $reports->where('status', 'resolved')->count() }}</div>
                    <div class="stat-label">Opgelost</div>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin') }}" class="sort-form">
            <label for="sort">Sorteer op datum:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="desc" {{ $sort == 'desc' ? 'selected' : '' }}>Nieuwste eerst</option>
                <option value="asc" {{ $sort == 'asc' ? 'selected' : '' }}>Oudste eerst</option>
            </select>
        </form>

        @if($reports->count() > 0)
        <div class="table-container">
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
                        <td><strong>#{{ $report->id }}</strong></td>
                        <td>{{ Str::limit($report->description, 50) }}</td>
                        <td>{{ $report->email ?? '-' }}</td>
                        <td>{{ $report->phone ?? '-' }}</td>

                        <td>
                            @if($report->photo_path)
                                <a href="{{ asset('storage/' . $report->photo_path) }}" target="_blank" class="badge badge-primary">
                                    📷 Bekijk
                                </a>
                            @else
                                <span style="color: var(--color-text-muted);">Geen foto</span>
                            @endif
                        </td>

                        <td>
                            <form method="POST" action="{{ route('admin.update', $report->id) }}" style="display: inline;">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="status-badge status-badge-{{ $report->status }}">
                                    <option value="open" {{ $report->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="working" {{ $report->status == 'working' ? 'selected' : '' }}>In behandeling</option>
                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Opgelost</option>
                                </select>
                            </form>
                        </td>

                        <td>{{ $report->created_at->format('d-m-Y H:i') }}</td>

                        <td>
                            <form method="POST" action="{{ route('admin.delete', $report->id) }}" onsubmit="return confirm('Weet je zeker dat je deze melding wilt verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">🗑️ Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <div class="empty-state-title">Geen meldingen gevonden</div>
            <div class="empty-state-message">Er zijn nog geen meldingen ingediend.</div>
        </div>
        @endif
    </div>
    
    @push('scripts')
        <script>
            @if(session('success'))
                Toast.success("{{ session('success') }}");
            @endif
            
            @if(session('error'))
                Toast.error("{{ session('error') }}");
            @endif
        </script>
    @endpush
@endsection
