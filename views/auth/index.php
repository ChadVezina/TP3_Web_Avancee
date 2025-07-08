{{ include('layouts/header.php', {title: 'Login'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">🔐</div>
        <h2>{{t('login.welcome')}}</h2>
        <p>{{t('login.connect_message')}}</p>
    </div>

    <div class="form-body">
        {% if errors is defined %}
        <div class="form-error">
            <ul>
                {% for error in errors %}
                <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
        {% endif %}

        {% if app.request.query.get('error') == 'security' %}
        <div class="form-error">
            <p>🚨 Activité suspecte détectée. Votre session a été fermée par mesure de sécurité.</p>
        </div>
        {% endif %}

        {% if app.request.query.get('error') == 'timeout' %}
        <div class="form-error">
            <p>⏰ Votre session a expiré. Veuillez vous reconnecter.</p>
        </div>
        {% endif %}

        <form method="post">
            <div class="form-group">
                <label for="username">👤 {{t('login.username')}}</label>
                <input type="text" id="username" name="username" value="{{ user.username }}" placeholder="{{t('login.username_placeholder')}}" required>
            </div>

            <div class="form-group">
                <label for="password">🔒 {{t('login.password')}}</label>
                <input type="password" id="password" name="password" placeholder="{{t('login.password_placeholder')}}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary block">🚀 {{t('login.signin')}}</button>
            </div>
        </form>
    </div>

    <div class="form-footer">
        <p>{{t('login.no_account_message')}}<a href="{{ base }}/user/create">{{t('login.create')}}</a></p>
    </div>
</div>
{{ include('layouts/footer.php')}}