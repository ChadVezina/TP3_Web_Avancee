<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }} | The Modern Blogger</title>
    <link rel="stylesheet" href="{{ asset }}css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="{{base}}/posts" class="nav-brand">
                <span class="logo">📝</span>
                <span>The Modern Blogger</span>
            </a>

            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="{{base}}/posts">📚 Posts</a></li>
                    <li><a href="{{base}}/categories">📁 Categories</a></li>
                    {% if logged_in %}
                    <li><a href="{{base}}/post/create">✍️ Write</a></li>
                    {% endif %}
                </ul>
            </div>

            <div class="nav-user">
                {% if logged_in %}
                <div class="nav-user-info">
                    <div class="nav-user-avatar">
                        {{ current_user|slice(0, 1)|upper }}
                    </div>
                    <span>{{ current_user }}</span>
                </div>
                <div class="nav-auth-links">
                    <a href="{{base}}/logout" class="btn-outline">🚪 Logout</a>
                </div>
                {% else %}
                <div class="nav-auth-links">
                    <a href="{{base}}/login" class="btn-outline">🔐 Login</a>
                    <a href="{{base}}/user/create" class="btn-solid">🎉 Join</a>
                </div>
                {% endif %}
            </div>
        </nav>
    </header>

    <main>