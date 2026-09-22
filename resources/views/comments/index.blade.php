
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Comment Board</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #222;
        }

        .container {
            width: min(900px, 92%);
            margin: 40px auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 22px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            margin-bottom: 16px;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #222;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        button,
        .clear-button {
            border: none;
            border-radius: 8px;
            padding: 11px 20px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        button {
            background: #222;
            color: white;
        }

        button:hover {
            background: #444;
        }

        .clear-button {
            background: #e5e5e5;
            color: #222;
        }

        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-form input {
            margin: 0;
            flex: 1;
        }

        .success {
            background: #d9f7e5;
            color: #146c3b;
            border: 1px solid #a8e4c1;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 20px;
        }

        .errors {
            background: #ffe1e1;
            color: #9f1d1d;
            border: 1px solid #f3b4b4;
            border-radius: 8px;
            padding: 14px 30px;
            margin-bottom: 20px;
        }

        .comments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comment-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 5px solid #222;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .comment-name {
            margin: 0 0 7px;
        }

        .comment-date {
            display: block;
            color: #777;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .comment-text {
            margin: 0;
            line-height: 1.8;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .empty-message {
            text-align: center;
            color: #777;
            padding: 30px;
        }

        @media (max-width: 600px) {
            .search-form {
                flex-direction: column;
                align-items: stretch;
            }

            .comments-header {
                display: block;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <header class="header">
            <h1>Comment Board</h1>
            <p>Add your comment and view comments from other visitors.</p>
        </header>

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <section class="card">
            <h2>Add a Comment</h2>

            <form action="{{ route('comments.store') }}" method="POST">
                @csrf

                <label for="name">Name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    maxlength="100"
                    placeholder="Enter your name"
                    required
                >

                <label for="comment">Comment</label>

                <textarea
                    id="comment"
                    name="comment"
                    maxlength="1000"
                    placeholder="Write your comment"
                    required
                >{{ old('comment') }}</textarea>

                <button type="submit">Add Comment</button>
            </form>
        </section>

        <section class="card">
            <h2>Search Comments</h2>

            <form
                action="{{ route('comments.index') }}"
                method="GET"
                class="search-form"
            >
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search by name or comment text"
                >

                <button type="submit">Search</button>

                @if ($search !== '')
                    <a
                        href="{{ route('comments.index') }}"
                        class="clear-button"
                    >
                        Clear Search
                    </a>
                @endif
            </form>
        </section>

        <section>
            <div class="comments-header">
                <h2>Comments</h2>
                <p>Results: {{ $comments->count() }}</p>
            </div>

            @forelse ($comments as $comment)
                <article class="comment-card">
                    <h3 class="comment-name">
                        {{ $comment->name }}
                    </h3>

                    <time class="comment-date">
                        {{ \Carbon\Carbon::parse($comment->created_at)->format('Y-m-d H:i') }}
                    </time>

                    <p class="comment-text">{{ $comment->comment }}</p>
                </article>
            @empty
                <div class="card empty-message">
                    @if ($search !== '')
                        No comments matched your search.
                    @else
                        No comments have been added yet.
                    @endif
                </div>
            @endforelse
        </section>
    </main>
</body>
</html>

