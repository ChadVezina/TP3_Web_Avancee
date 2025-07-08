{{ include('layouts/header.php', {title: 'Create Post'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">✍️</div>
        <h1>{{t('posts.create')}}</h1>
        <p>{{t('posts.share')}}</p>
    </div>

    <div class="form-body">
        <form action="{{base}}/post/store" method="post">
            <div class="form-group">
                <label for="title">📝 {{t('posts.title')}}</label>
                <input type="text" id="title" name="title" value="{{post.title}}" placeholder="{{t('posts.title_placeholder')}}" required>
                {% if errors.title is defined %}
                <span class="field-error">{{errors.title}}</span>
                {% endif %}
            </div>

            <div class="form-group">
                <label for="category_id">📁 {{t('categories.title')}}</label>
                <select id="category_id" name="category_id" required>
                    <option value="">{{t('categories.choose_category')}}</option>
                    {% for category in categories %}
                    <option value="{{ category.id }}" {% if category.id == post.category_id %} selected {% endif %}>{{ category.name }}</option>
                    {% endfor %}
                </select>
                {% if errors.category_id is defined %}
                <span class="field-error">{{errors.category_id}}</span>
                {% endif %}
            </div>

            <div class="form-group">
                <label for="content">📄 {{t('posts.content')}}</label>
                <textarea id="content" name="content" placeholder="{{t('posts.content_placeholder')}}" required>{{post.content}}</textarea>
                {% if errors.content is defined %}
                <span class="field-error">{{errors.content}}</span>
                {% endif %}
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary">🚀 {{t('posts.create')}}</button>
                <a href="{{ base }}/posts" class="btn secondary">❌ {{t('posts.cancel')}}</a>
            </div>
        </form>
    </div>
</div>
{{ include('layouts/footer.php')}}