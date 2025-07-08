{{ include('layouts/header.php', {title: 'Journal de bord'})}}

<div class="activity-log-container">
    <div class="activity-log-header">
        <h1 class="activity-log-title">📊 {{t('logs.title')}}</h1>

        <div class="activity-log-actions">
            <div class="clear-logs-dropdown">
                <button class="btn red">
                    🗑️ {{t('logs.clear')}}
                    <span style="font-size: 0.8em;">▼</span>
                </button>
                <div class="clear-logs-menu">
                    <div class="clear-logs-menu-items">
                        <a href="{{ base }}/activity-logs/clear?type=10" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer les 10 logs les plus anciens ?')">
                            🗑️ {{t('logs.delete10')}}
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=50" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer les 50 logs les plus anciens ?')">
                            🗑️ {{t('logs.delete50')}}
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=100" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer les 100 logs les plus anciens ?')">
                            🗑️ {{t('logs.delete100')}}
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=500" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer les 500 logs les plus anciens ?')">
                            🗑️ {{t('logs.delete500')}}
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=old" class="clear-logs-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer tous les logs de plus de 6 mois ?')">
                            🗑️ {{t('logs.delete_old')}}
                        </a>
                        <a href="{{ base }}/activity-logs/clear?type=all" class="clear-logs-item danger" onclick="return confirm('⚠️ ATTENTION ⚠️\n\nCette action supprimera TOUS les logs de manière définitive.\n\nÊtes-vous absolument certain de vouloir continuer ?')">
                            ⚠️ {{t('logs.delete_all')}}
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ base }}/activity-logs" class="btn secondary">🔄 {{t('logs.refresh')}}</a>
            <a href="{{ base }}/user/admin-create" class="btn primary">👑 {{t('logs.create_admin')}}</a>
        </div>
    </div>

    {% if flash_message %}
    <div class="flash-message">
        {{ flash_message }}
    </div>
    {% endif %}

    <div class="activity-log-card">
        <div class="activity-log-card-header">
            <h3 class="activity-log-card-title">📋 {{t('logs.recent_activities')}} ({{ totalLogs }} total)</h3>
        </div>

        {% if logs %}
        <div class="activity-log-table-container">
            <table class="activity-log-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">📅 {{t('logs.table_date')}}</th>
                        <th style="width: 100px;">👤 {{t('logs.table_user')}}</th>
                        <th style="width: 200px;">🎯 {{t('logs.table_action')}}</th>
                        <th style="width: 150px;">📍 {{t('logs.table_url')}}</th>
                        <th style="width: 140px;">🌐 {{t('logs.table_ip')}}</th>
                        <th style="width: 200px;">🖥️ {{t('logs.table_user_agent')}}</th>
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
                <a href="{{ base }}/activity-logs?page={{ currentPage - 1 }}" class="btn secondary" style="padding: 8px 12px;">{{t('logs.previous_page')}}</a>
                {% endif %}

                <span class="activity-log-pagination-info">
                    Page {{ currentPage }} sur {{ totalPages }}
                </span>

                {% if currentPage < totalPages %}
                <a href="{{ base }}/activity-logs?page={{ currentPage + 1 }}" class="btn secondary" style="padding: 8px 12px;">{{t('logs.next_page')}}</a>
                {% endif %}
            </div>
        </div>
        {% endif %}
        {% else %}
        <div class="activity-log-empty">
            <div class="activity-log-empty-icon">📊</div>
            <h3>{{t('logs.empty')}}</h3>
            <p>{{t('logs.empty_message')}}</p>
        </div>
        {% endif %}
    </div>
</div>

{{ include('layouts/footer.php')}}