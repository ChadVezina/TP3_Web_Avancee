{{ include('layouts/header.php', {title: 'Création d\'utilisateur (Admin)'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">👑</div>
        <h2>Création d'utilisateur (Admin)</h2>
        <p>Créer un nouvel utilisateur avec privilèges spécifiques</p>
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
                <input type="email" id="email" name="email" value="{{ user.email }}" placeholder="Enter email address" required>
            </div>

            <div class="form-group">
                <label for="password">🔒 Password</label>
                <input type="password" id="password" name="password" placeholder="Create a secure password (min 8 characters)" required>
            </div>

            <div class="form-group">
                <label for="privilege_id">🎯 Privilege Level</label>
                <select id="privilege_id" name="privilege_id" required>
                    <option value="">Sélectionner un niveau de privilège...</option>
                    {% for privilege in privileges %}
                    <option value="{{ privilege.id }}" {% if user.privilege_id == privilege.id %} selected {% endif %}>
                        {% if privilege.id == 1 %}👑 Admin{% elseif privilege.id == 2 %}🛡️ Modérateur{% else %}👤 Utilisateur{% endif %} - {{ privilege.privilege }}
                    </option>
                    {% endfor %}
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary block">👑 Créer l'utilisateur</button>
                <a href="{{ base }}/activity-logs" class="btn secondary block">📊 Retour aux logs</a>
            </div>
        </form>
    </div>
</div>

{{ include('layouts/footer.php') }}