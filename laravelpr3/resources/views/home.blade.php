@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@section('title', 'Melding maken - Gemeente Rotterdam')
@section('breadcrumb', 'Melding openbare ruimte')

@section('content')
    <div class="gov-container">
        <div class="gov-header">
            <h1>Melding openbare ruimte</h1>
            <p>Ziet u iets in de openbare ruimte dat niet klopt? Zoals een kapotte stoep, zwerfafval of een defecte straatlantaarn? Doe hier uw melding.</p>
        </div>
        
        <div class="info-box">
            <h3>ℹ️ Wat kunt u melden?</h3>
            <ul>
                <li>Beschadigingen aan straten, stoepen en fietspaden</li>
                <li>Kapotte lantaarnpalen of verkeersborden</li>
                <li>Overlast door zwerfvuil of graffiti</li>
                <li>Problemen met groen en bomen</li>
            </ul>
        </div>

            <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data" id="reportForm">
                @csrf

                <div class="form-group">
                    <label for="description">Beschrijving</label>
                    <textarea name="description" id="description" required maxlength="500"></textarea>
                    <small class="char-counter">0 / 500 tekens</small>
                </div>

                <div class="form-group">
                    <label for="email">E-mail (optioneel)</label>
                    <input type="email" name="email" id="email" placeholder="naam@voorbeeld.nl">
                </div>

                <div class="form-group">
                    <label for="phone">Telefoonnummer (optioneel)</label>
                    <input type="text" name="phone" id="phone" placeholder="06 12345678">
                </div>

                <div class="form-group">
                    <label for="photo">Foto (optioneel)</label>
                    <div class="file-input-wrapper">
                        <input type="file" name="photo" id="photo" accept="image/*">
                        <label for="photo" class="file-input-label">
                            <span>📷 Klik of sleep foto hierheen</span>
                        </label>
                    </div>
                </div>

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" id="locateBtn" class="secondary">📍 Gebruik mijn locatie</button>
                    <button type="submit" id="submitBtn">Verstuur Melding</button>
                </div>
            </form>
        </div>

        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 40px; margin-top: 30px;">
            <h2 style="color: #00811f; font-size: 24px; margin: 0 0 20px 0; font-weight: 700; border-left: 4px solid #00811f; padding-left: 16px;">Locatie op de kaart</h2>
            <div id="map" style="height: 500px; border-radius: 4px; border: 2px solid #d0d0d0;"></div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="{{ asset('js/home.js') }}"></script>
        <script>
            // Character counter
            const textarea = document.getElementById('description');
            const counter = document.querySelector('.char-counter');
            
            textarea.addEventListener('input', () => {
                const length = textarea.value.length;
                counter.textContent = `${length} / 500 tekens`;
                
                if (length > 450) {
                    counter.style.color = 'var(--color-warning)';
                } else {
                    counter.style.color = 'var(--color-text-muted)';
                }
            });
            
            // File input preview
            const fileInput = document.getElementById('photo');
            const fileLabel = fileInput.nextElementSibling;
            
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    const fileName = e.target.files[0].name;
                    fileLabel.querySelector('span').textContent = `✓ ${fileName}`;
                    fileLabel.classList.add('has-file');
                } else {
                    fileLabel.querySelector('span').textContent = '📷 Klik of sleep foto hierheen';
                    fileLabel.classList.remove('has-file');
                }
            });
            
            // Form submission with loading state
            const form = document.getElementById('reportForm');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', (e) => {
                LoadingState.setButtonLoading(submitBtn, true);
            });
            
            // Show success message if redirected from submission
            @if(session('success'))
                Toast.success("{{ session('success') }}");
            @endif
            
            @if(session('error'))
                Toast.error("{{ session('error') }}");
            @endif
        </script>
    @endpush
