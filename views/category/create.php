{{ include('layouts/header.php', {title: 'Create Category'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">📁</div>
        <h1>{{t('categories.create')}}</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9; font-weight: 300;">{{t('categories.create_description')}}</p>
    </div>

    <div class="form-body">
        <form action="{{base}}/category/store" method="post">
            <div class="form-group">
                <label for="name">🏷️ {{t('categories.name')}}</label>
                <input type="text" id="name" name="name" value="{{category.name}}" placeholder="{{t('categories.name_placeholder')}}" required>
                {% if errors.name is defined %}
                <span class="field-error">{{errors.name}}</span>
                {% endif %}
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary">✨ {{t('categories.create')}}</button>
                <a href="{{ base }}/categories" class="btn secondary">❌ {{t('categories.cancel')}}</a>
            </div>
        </form>
    </div>
</div>
{{ include('layouts/footer.php')}}