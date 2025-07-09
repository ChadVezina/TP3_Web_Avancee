<!DOCTYPE html>
<html lang="{{ current_language }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }} | {{ t('misc.site_title') }}</title>
    <link rel="stylesheet" href="{{ asset }}css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            {% if logged_in and auth_privilege_id == 1 %}
            <a href="{{ lang_url('activity-logs') }}" class="nav-brand">
                <span class="logo">📝</span>
                <span>{{ t('misc.site_title') }}</span>
            </a>
            {% else %}
            <a href="{{ lang_url('') }}" class="nav-brand">
                <span class="logo">📝</span>
                <span>{{ t('misc.site_title') }}</span>
            </a>
            {% endif %}

            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="{{ lang_url('posts') }}">📚 {{ t('nav.posts') }}</a></li>
                    {% if logged_in %}
                    <li><a href="{{ lang_url('categories') }}">📁 {{ t('nav.categories') }}</a></li>
                    {% endif %}
                    {% if logged_in %}
                    <li><a href="{{ lang_url('post/create') }}">✍️ {{ t('btn.create') }}</a></li>
                    {% endif %}
                    {% if logged_in and auth_privilege_id == 1 %}
                    <li><a href="{{ lang_url('activity-logs') }}">📊 {{ t('nav.activity_logs') }}</a></li>
                    {% endif %}
                </ul>
            </div>

            <div class="nav-user">
                <!-- Language Switcher -->
                <div class="language-switcher">
                    <select onchange="switchLanguage(this.value)" class="language-select">
                        {% for lang in supported_languages %}
                        <option value="{{ lang }}" {% if lang == current_language %}selected{% endif %}>
                            {% if lang == 'fr' %}🇫🇷 Français{% elseif lang == 'es' %}🇪🇸 Español{% else %}🇺🇸 English{% endif %}
                        </option>
                        {% endfor %}
                    </select>
                </div>

                {% if logged_in %}
                <div class="nav-user-info">
                    <div class="nav-user-avatar">
                        {{ current_user|slice(0, 1)|upper }}
                    </div>
                    <span>{{ current_user }}</span>
                </div>
                <div class="nav-auth-links">
                    <a href="{{ lang_url('logout') }}" class="btn-outline">🚪 {{ t('nav.logout') }}</a>
                </div>
                {% else %}
                <div class="nav-auth-links">
                    <a href="{{ lang_url('login') }}" class="btn-outline">🔐 {{ t('nav.login') }}</a>
                    <a href="{{ lang_url('user/create') }}" class="btn-solid">🎉 {{ t('nav.register') }}</a>
                </div>
                {% endif %}
            </div>
        </nav>
    </header>

    <script>
        function switchLanguage(lang) {
            const url = new URL(window.location);
            url.searchParams.set('lang', lang);
            window.location.href = url.toString();
        }
    </script>

    <main>