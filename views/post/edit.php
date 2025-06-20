{{ include('layouts/header.php', {title: 'Edit Post'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">✏️</div>
        <h1>Edit Post</h1>
        <p>Update your post content</p>
    </div>

    <div class="form-body">
        <form method="post">
            <div class="form-group">
                <label for="title">📝 Post Title</label>
                <input type="text" id="title" name="title" value="{{post.title}}" required>
                {% if errors.title is defined %}
                <span class="field-error">{{errors.title}}</span>
                {% endif %}
            </div>

            <div class="form-group">
                <label for="category_id">📁 Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Choose a category...</option>
                    {% for category in categories %}
                    <option value="{{ category.id }}" {% if category.id == post.category_id %} selected {% endif %}>{{ category.name }}</option>
                    {% endfor %}
                </select>
                {% if errors.category_id is defined %}
                <span class="field-error">{{errors.category_id}}</span>
                {% endif %}
            </div>

            <div class="form-group">
                <label for="content">📄 Content</label>
                <textarea id="content" name="content" required>{{post.content}}</textarea>
                {% if errors.content is defined %}
                <span class="field-error">{{errors.content}}</span>
                {% endif %}
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary">💾 Update Post</button>
                <a href="{{ base }}/post/show?id={{ post.id }}" class="btn secondary">👁️ View Post</a>
            </div>
        </form>
    </div>
</div>
{{ include('layouts/footer.php')}}