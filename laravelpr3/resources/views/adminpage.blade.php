
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<div class="navbar">
    <a href="../views/home.blade.php">Home</a>
</div>

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
            <th>Titel</th>
            <th>Beschrijving</th>
            <th>Status</th>
            <th>Aangemaakt op</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($reports as $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->title }}</td>
                <td>{{ $report->description }}</td>
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
                        <button type="submit">Verwijderen</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
