{{ include('layouts/header.php', {title: 'Create Post'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">✍️</div>
        <h1>Create New Post</h1>
        <p>Share your thoughts with the community</p>
    </div>

    <div class="form-body">
        <form action="{{base}}/post/store" method="post">
            <div class="form-group">
                <label for="title">📝 Post Title</label>
                <input type="text" id="title" name="title" value="{{post.title}}" placeholder="Enter an engaging title for your post" required>
                {% if errors.title is defined %}
                <span class="field-error">{{errors.title}}</span>
                {% endif %}
            </div>

            <div class="form-group">
                <label for="category_id">📁 Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Choose a category for your post...</option>
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
                <textarea id="content" name="content" placeholder="Write your post content here. Share your ideas, experiences, or insights..." required>{{post.content}}</textarea>
                {% if errors.content is defined %}
                <span class="field-error">{{errors.content}}</span>
                {% endif %}
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary">🚀 Publish Post</button>
                <a href="{{ base }}/posts" class="btn secondary">❌ Cancel</a>
            </div>
        </form>
    </div>
</div>
{{ include('layouts/footer.php')}}