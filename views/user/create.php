{{ include('layouts/header.php', {title: 'Registration'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">👋</div>
        <h2>{{t('register.title')}}</h2>
        <p>{{t('register.connect_message')}}</p>
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

        <form method="post">
            <div class="form-group">
                <label for="username">👤 {{t('register.username')}}</label>
                <input type="text" id="username" name="username" value="{{ user.username }}" placeholder="{{t('register.username_placeholder')}}" required>
            </div>

            <div class="form-group">
                <label for="email">📧 {{t('register.email')}}</label>
                <input type="email" id="email" name="email" value="{{ user.email }}" placeholder="{{t('register.email_placeholder')}}" required>
            </div>

            <div class="form-group">
                <label for="password">🔒 {{t('register.password')}}</label>
                <input type="password" id="password" name="password" placeholder="{{t('register.password_placeholder')}}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary block">🎉 {{t('register.create_account')}}</button>
            </div>
        </form>
    </div>

    <div class="form-footer">
        <p>{{t('register.already_have_account')}} <a href="{{ base }}/login">{{t('register.login_here')}}</a></p>
    </div>
</div>
{{ include('layouts/footer.php')}}