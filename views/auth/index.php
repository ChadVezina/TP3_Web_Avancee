{{ include('layouts/header.php', {title: 'Login'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">🔐</div>
        <h2>Welcome Back</h2>
        <p>Sign in to your account</p>
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
                <label for="username">👤 Username</label>
                <input type="text" id="username" name="username" value="{{ user.username }}" placeholder="Enter your username" required>
            </div>

            <div class="form-group">
                <label for="password">🔒 Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary block">🚀 Sign In</button>
            </div>
        </form>
    </div>

    <div class="form-footer">
        <p>Don't have an account? <a href="{{ base }}/user/create">Create one here</a></p>
    </div>
</div>
{{ include('layouts/footer.php')}}