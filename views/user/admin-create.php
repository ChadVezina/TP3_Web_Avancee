{{ include('layouts/header.php', {title: 'Création d\'utilisateur (Admin)'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">👑</div>
        <h2>{{t('admin.create_user')}}</h2>
        <p>{{t('admin.create_user_description')}}</p>
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
                <label for="username">👤 {{t('admin.username')}}</label>
                <input type="text" id="username" name="username" value="{{ user.username }}" placeholder="{{t('admin.username_placeholder')}}" required>
            </div>

            <div class="form-group">
                <label for="email">📧 {{t('admin.email')}}</label>
                <input type="email" id="email" name="email" value="{{ user.email }}" placeholder="{{t('admin.email_placeholder')}}" required>
            </div>

            <div class="form-group">
                <label for="password">🔒 {{t('admin.password')}}</label>
                <input type="password" id="password" name="password" placeholder="{{t('admin.password_placeholder')}}" required>
            </div>

            <div class="form-group">
                <label for="privilege_id">🎯 {{t('admin.privilege_id')}}</label>
                <select id="privilege_id" name="privilege_id" required>
                    <option value="">{{t('admin.privilege_id_placeholder')}}</option>
                    {% for privilege in privileges %}
                    <option value="{{ privilege.id }}" {% if user.privilege_id == privilege.id %} selected {% endif %}>
                        {% if privilege.id == 1 %}{{t('admin.privilege_admin')}}{% elseif privilege.id == 2 %}{{t('admin.privilege_moderator')}}{% else %}{{t('admin.privilege_user')}}{% endif %} - {{ privilege.privilege }}
                    </option>
                    {% endfor %}
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary block">{{t('admin.create_user_button')}}</button>
                <a href="{{ base }}/activity-logs" class="btn secondary block">{{t('admin.back_to_logs')}}</a>
            </div>
        </form>
    </div>
</div>

{{ include('layouts/footer.php') }}