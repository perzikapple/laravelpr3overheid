@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@section('title', 'Melding doen')

    @section('content')
        <div class="container">
            <h2>Doe een melding</h2>

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

            <div id="map" style="height: 500px; margin-top: 24px; border-radius: 8px;" class="skeleton"></div>
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
