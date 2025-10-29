<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Submit Report</title>
</head>
<body>
@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
    @csrf
    <div>
        <label>Title</label>
        <input name="title" value="{{ old('title') }}" required>
        @error('title')<div>{{ $message }}</div>@enderror
    </div>

    <div>
        <label>Description</label>
        <textarea name="description">{{ old('description') }}</textarea>
    </div>

    <div>
        <label>Photo</label>
        <input type="file" name="photo" accept="image/*">
    </div>

    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

    <div>
        <button type="button" id="locateBtn">Use my location</button>
        <button type="submit">Submit report</button>
    </div>
</form>

<script>
    document.getElementById('locateBtn').addEventListener('click', function () {
        if (!navigator.geolocation) return alert('Geolocation not supported');
        navigator.geolocation.getCurrentPosition(function(pos){
            document.getElementById('latitude').value = pos.coords.latitude;
            document.getElementById('longitude').value = pos.coords.longitude;
            alert('Location captured');
        }, function(){ alert('Unable to get location'); });
    });
</script>
</body>
</html>
