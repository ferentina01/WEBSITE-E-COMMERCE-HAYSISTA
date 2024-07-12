<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts List</title>
    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Posts</h1>
        <ul class="list-group">
            @foreach ($posts as $post)
                <li class="list-group-item">{{ $post->title }}</li>
            @endforeach
        </ul>

        <!-- Pagination Links -->
        <div class="mt-3">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- Bootstrap JavaScript (optional, if needed for components) -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>
