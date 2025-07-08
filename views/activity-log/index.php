{{ include('layouts/header.php', {title: 'Journal de bord'})}}

<div class="activity-log-container">
    <div class="activity-log-header">
        <h1 class="activity-log-title">📊 Journal de bord</h1>
        
        <div class="activity-log-actions">
            <!-- Options de suppression -->
            <div class="clear-logs-dropdown">
                <button class="btn red">
                    🗑️ Nettoyer les logs
                    <span style="font-size: 0.8em;">▼</span>
                </button>
                <div class="clear-logs-menu">
                    <div class="clear-logs-menu-items">
                        <a href="{{ base }}/activity-logs/clear?type=10" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer les 10 logs les plus anciens ?')">
                            🗑️ Supprimer 10 plus anciens
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=50" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer les 50 logs les plus anciens ?')">
                            🗑️ Supprimer 50 plus anciens
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=100" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer les 100 logs les plus anciens ?')">
                            🗑️ Supprimer 100 plus anciens
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=500" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer les 500 logs les plus anciens ?')">
                            🗑️ Supprimer 500 plus anciens
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=old" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer tous les logs de plus de 6 mois ?')">
                            🗑️ Supprimer logs > 6 mois
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=all" class="clear-logs-item danger" onclick="return confirm('⚠️ ATTENTION ⚠️\n\nCette action supprimera TOUS les logs de manière définitive.\n\nÊtes-vous absolument certain de vouloir continuer ?')">
                            ⚠️ Supprimer TOUS les logs
                        </a>
                    </div>
                </div>
            </div>
            
            <a href="{{ base }}/activity-logs" class="btn secondary">🔄 Actualiser</a>
        </div>
    </div>

    {% if flash_message %}
    <div class="flash-message">
        {{ flash_message }}
    </div>
    {% endif %}

    <div class="activity-log-card">
        <div class="activity-log-card-header">
            <h3 class="activity-log-card-title">📋 Activités récentes ({{ totalLogs }} total)</h3>
        </div>

        {% if logs %}
        <div class="activity-log-table-container">
            <table class="activity-log-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">📅 Date</th>
                        <th style="width: 100px;">👤 Utilisateur</th>
                        <th style="width: 200px;">🎯 Action</th>
                        <th style="width: 150px;">📍 URL</th>
                        <th style="width: 140px;">🌐 Adresse IP</th>
                        <th style="width: 200px;">🖥️ User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    {% for log in logs %}
                    {% set badgeClass = 'default' %}
                    {% if 'Échec' in log.action or 'suppression' in log.action %}
                        {% set badgeClass = 'error' %}
                    {% elseif 'création' in log.action %}
                        {% set badgeClass = 'create' %}
                    {% elseif 'modification' in log.action %}
                        {% set badgeClass = 'edit' %}
                    {% endif %}
                    
                    <tr>
                        <td class="activity-log-date">
                            {{ log.created_at|date('d/m/Y H:i') }}
                        </td>
                        <td class="activity-log-username">
                            {{ log.username ?: 'Visiteur' }}
                        </td>
                        <td class="activity-log-action">
                            <span class="activity-log-action-badge {{ badgeClass }}">
                                {{ log.action }}
                            </span>
                        </td>
                        <td class="activity-log-url">
                            {{ log.url ?: '-' }}
                        </td>
                        <td class="activity-log-ip">
                            {{ log.ip_address ?: '-' }}
                        </td>
                        <td class="activity-log-user-agent" title="{{ log.user_agent }}">
                            {{ log.user_agent ?: '-' }}
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {% if totalPages > 1 %}
        <div class="activity-log-pagination">
            <div class="activity-log-pagination-controls">
                {% if currentPage > 1 %}
                <a href="{{ base }}/activity-logs?page={{ currentPage - 1 }}" class="btn secondary" style="padding: 8px 12px;">← Précédent</a>
                {% endif %}
                
                <span class="activity-log-pagination-info">
                    Page {{ currentPage }} sur {{ totalPages }}
                </span>
                
                {% if currentPage < totalPages %}
                <a href="{{ base }}/activity-logs?page={{ currentPage + 1 }}" class="btn secondary" style="padding: 8px 12px;">Suivant →</a>
                {% endif %}
            </div>
        </div>
        {% endif %}
        {% else %}
        <div class="activity-log-empty">
            <div class="activity-log-empty-icon">📊</div>
            <h3>Aucune activité enregistrée</h3>
            <p>Le journal de bord est vide.</p>
        </div>
        {% endif %}
    </div>
</div>

{{ include('layouts/footer.php')}}