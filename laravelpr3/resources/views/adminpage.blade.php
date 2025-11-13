@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@section('title', 'Beheer - Gemeente Rotterdam')
@section('breadcrumb', 'Meldingen beheren')

@section('content')
    <div class="gov-admin-header">
        <h1>Meldingen beheren</h1>
        <p style="color: #666; margin: 8px 0 0 0; font-size: 16px;">Overzicht en beheer van alle openbare ruimte meldingen</p>
    </div>
    
    <div class="gov-stats-grid">
        <div class="gov-stat-card">
            <div class="gov-stat-value">{{ $reports->where('status', 'open')->count() }}</div>
            <div class="gov-stat-label">Open</div>
        </div>
        <div class="gov-stat-card">
            <div class="gov-stat-value">{{ $reports->where('status', 'working')->count() }}</div>
            <div class="gov-stat-label">In behandeling</div>
        </div>
        <div class="gov-stat-card">
            <div class="gov-stat-value">{{ $reports->where('status', 'resolved')->count() }}</div>
            <div class="gov-stat-label">Opgelost</div>
        </div>
    </div>

    <!-- Kaart met alle meldingen -->
    @if($reports->where('latitude', '!=', null)->count() > 0)
    <div class="gov-section">
        <h2>Meldingen op kaart</h2>
        <div id="adminMap" style="height: 500px; border-radius: 4px; border: 2px solid #d0d0d0;"></div>
    </div>
    @endif

    <div class="gov-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 20px; flex-wrap: wrap;">
            <h2 style="margin: 0;">Alle meldingen</h2>
            <div style="display: flex; gap: 20px; align-items: center;">
                <!-- Zoekfunctie -->
                <form method="GET" action="{{ route('admin') }}" style="margin: 0; display: flex; gap: 8px; align-items: center;">
                    <input type="text" name="search" placeholder="Zoek op ID..." value="{{ request('search') }}" style="padding: 8px 12px; border: 2px solid #d0d0d0; border-radius: 4px; font-size: 14px; width: 150px;">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <button type="submit" style="padding: 8px 16px; background: #00811f; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; font-weight: 600;">Zoeken</button>
                    @if(request('search'))
                        <a href="{{ route('admin') }}?sort={{ $sort }}" style="padding: 8px 16px; background: #666; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 600;">Reset</a>
                    @endif
                </form>
                
                <!-- Sorteer -->
                <form method="GET" action="{{ route('admin') }}" style="margin: 0;">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <label for="sort" style="margin-right: 8px; font-size: 14px; color: #666;">Sorteer:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()" style="padding: 8px 12px; border: 2px solid #d0d0d0; border-radius: 4px; font-size: 14px; background: white; cursor: pointer;">
                        <option value="desc" {{ $sort == 'desc' ? 'selected' : '' }}>Nieuwste eerst</option>
                        <option value="asc" {{ $sort == 'asc' ? 'selected' : '' }}>Oudste eerst</option>
                    </select>
                </form>
            </div>
        </div>

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
                                    📍 Bekijk op Google Maps
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
            @if(request('search'))
                <div class="empty-state-message">Geen melding gevonden met ID: {{ request('search') }}</div>
                <a href="{{ route('admin') }}?sort={{ $sort }}" style="display: inline-block; margin-top: 16px; padding: 10px 20px; background: #00811f; color: white; text-decoration: none; border-radius: 4px; font-weight: 600;">Alle meldingen tonen</a>
            @else
                <div class="empty-state-message">Er zijn nog geen meldingen ingediend.</div>
            @endif
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
            const adminMap = L.map('adminMap', {
                minZoom: 11,
                maxZoom: 18,
                maxBounds: [
                    [51.8, 4.3],   // Zuidwest hoek van Rotterdam
                    [52.0, 4.65]    // Noordoost hoek van Rotterdam
                ]
            }).setView([51.9225, 4.47917], 12); // Rotterdam centrum

            L.tileLayer('https://service.pdok.nl/brt/achtergrondkaart/wmts/v2_0/standaard/EPSG:3857/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: 'Kaartgegevens &copy; <a href="https://www.kadaster.nl">Kadaster</a>'
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
