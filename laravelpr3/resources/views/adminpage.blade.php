@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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

        <!-- Kaart met alle meldingen -->
        @if($reports->where('latitude', '!=', null)->count() > 0)
        <div style="margin-bottom: 2rem;">
            <h2 style="margin-bottom: 1rem;">Meldingen op kaart</h2>
            <div id="adminMap" style="height: 500px; border-radius: 8px; border: 2px solid var(--color-border);"></div>
        </div>
        @endif

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
                    <th>Locatie</th>
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
                        <td>
                            @if($report->email)
                                <a href="mailto:{{ $report->email }}" style="color: var(--color-primary); text-decoration: none;">
                                    📧 {{ $report->email }}
                                </a>
                            @else
                                <span style="color: var(--color-text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($report->phone)
                                <a href="tel:{{ $report->phone }}" style="color: var(--color-primary); text-decoration: none;">
                                    📞 {{ $report->phone }}
                                </a>
                            @else
                                <span style="color: var(--color-text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($report->latitude && $report->longitude)
                                <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="badge badge-primary" style="white-space: nowrap;">
                                    📍 Bekijk kaart
                                </a>
                            @else
                                <span style="color: var(--color-text-muted);">Geen locatie</span>
                            @endif
                        </td>

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
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            @if(session('success'))
                Toast.success("{{ session('success') }}");
            @endif
            
            @if(session('error'))
                Toast.error("{{ session('error') }}");
            @endif

            // Initialiseer de kaart voor admin
            @if($reports->where('latitude', '!=', null)->count() > 0)
            const adminMap = L.map('adminMap').setView([51.9225, 4.47917], 12); // Rotterdam centrum

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(adminMap);

            // Voeg alle meldingen toe als markers
            const reports = @json($reports->where('latitude', '!=', null)->values());
            
            reports.forEach(report => {
                if (report.latitude && report.longitude) {
                    // Kies kleur op basis van status
                    let markerColor = 'red';
                    if (report.status === 'working') markerColor = 'orange';
                    if (report.status === 'resolved') markerColor = 'green';

                    // Maak custom marker icon
                    const icon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background-color: ${markerColor}; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
                        iconSize: [25, 25],
                        iconAnchor: [12, 12]
                    });

                    const marker = L.marker([report.latitude, report.longitude], { icon: icon }).addTo(adminMap);
                    
                    // Popup met melding info
                    const popupContent = `
                        <div style="min-width: 200px;">
                            <strong>Melding #${report.id}</strong><br>
                            <strong>Status:</strong> ${report.status === 'open' ? 'Open' : report.status === 'working' ? 'In behandeling' : 'Opgelost'}<br>
                            <strong>Beschrijving:</strong> ${report.description.substring(0, 100)}${report.description.length > 100 ? '...' : ''}<br>
                            ${report.email ? `<strong>Email:</strong> ${report.email}<br>` : ''}
                            ${report.phone ? `<strong>Telefoon:</strong> ${report.phone}<br>` : ''}
                            <strong>Datum:</strong> ${new Date(report.created_at).toLocaleDateString('nl-NL')}
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                }
            });

            // Pas de kaart aan om alle markers te tonen
            if (reports.length > 0) {
                const bounds = L.latLngBounds(reports.map(r => [r.latitude, r.longitude]));
                adminMap.fitBounds(bounds, { padding: [50, 50] });
            }
            @endif
        </script>
    @endpush
@endsection
