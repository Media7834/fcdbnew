<!DOCTYPE html>
<html>
<head>
    <title>Image Upload and Search</title>
</head>
<body>
    <h1>Image Upload and Search</h1>

    <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <button type="submit">Upload Image</button>
    </form>

    @if (isset($results))
        <!-- Display the recognition result -->
        @if (isset($output))
            <h2>Recognition Result: {{ $output }}</h2>
        @endif

        <h2>Search Results:</h2>
        @if ($results->count() > 0)
            <ul>
                @foreach ($results as $result)
                    <li>{{ $result->number }}</li>
                @endforeach
            </ul>
        @else
            <p>No results found.</p>
        @endif

        <!-- Display the connection status -->
        @if (isset($connected))
            @if ($connected)
                <p>Connected to the database and table.</p>
            @else
                <p>Unable to connect to the database or table.</p>
            @endif
        @endif

        <!-- Display the matching record and additional information -->
        @if (isset($record))
            <h2>Matching Record:</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Age</th>
                    <th>Department</th>
                    <th>GPA</th>
                </tr>
                <tr>
                    <td>{{ $record->name }}</td>
                    <td>{{ $record->number }}</td>
                    <td>{{ $record->age }}</td>
                    <td>{{ $record->dpt }}</td>
                    <td>{{ $record->gpa }}</td>
                </tr>
            </table>
        @endif
    @endif
</body>
</html>
