{{ include('layouts/header.php', {title: 'Registration'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">👋</div>
        <h2>Join Our Community</h2>
        <p>Create your account to get started</p>
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
                <label for="username">👤 Username</label>
                <input type="text" id="username" name="username" value="{{ user.username }}" placeholder="Choose a unique username" required>
            </div>

            <div class="form-group">
                <label for="email">📧 Email Address</label>
                <input type="email" id="email" name="email" value="{{ user.email }}" placeholder="Enter your email address" required>
            </div>

            <div class="form-group">
                <label for="password">🔒 Password</label>
                <input type="password" id="password" name="password" placeholder="Create a secure password (min 8 characters)" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary block">🎉 Create Account</button>
            </div>
        </form>
    </div>

    <div class="form-footer">
        <p>Already have an account? <a href="{{ base }}/login">Sign in here</a></p>
    </div>
</div>
{{ include('layouts/footer.php')}}