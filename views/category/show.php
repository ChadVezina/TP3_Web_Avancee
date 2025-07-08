{{ include('layouts/header.php', {title: category.name ~ ' - Posts'})}}
<div style="max-width: 1200px; margin: 0 auto;">
    <!-- Category Header -->
    <div style="background: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="color: #333; margin: 0 0 10px 0; font-size: 2.5em; display: flex; align-items: center; gap: 15px;">
                    📁 {{ category.name }}
                </h1>
                <p style="color: #666; margin: 0; font-size: 1.1em;">
                    {% if posts %}
                    {{ posts|length }} post{{ posts|length != 1 ? 's' : '' }} in this category
                    {% else %}
                    {{t('categories.no_posts_in_category')}}
                    {% endif %}
                </p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ base }}/categories" class="btn secondary">{{t('categories.all')}}</a>
                {% if logged_in %}
                <a href="{{ base }}/category/edit?id={{ category.id }}" class="btn">✏️ {{t('categories.edit_description')}}</a>
                <a href="{{ base }}/post/create" class="btn primary">✍️ {{t('posts.create')}}</a>
                {% endif %}
            </div>
        </div>
    </div>

    <!-- Posts in Category -->
    {% if posts %}
    <div class="cards-container">
        {% for post in posts %}
        <article class="card">
            <div class="card-header">
                <h2 class="card-title">{{ post.title }}</h2>
                <div class="card-meta">
                    <div class="card-meta-item">
                        <span class="card-badge category">📁 {{ post.category_name }}</span>
                    </div>
                    <div class="card-meta-item">
                        <span class="card-badge author">👤 {{ post.author_name ?? 'Unknown Author' }}</span>
                    </div>
                    <div class="card-meta-item">
                        <span class="card-badge date">📅 {{ post.created_at }}</span>
                    </div>
                </div>
            </div>

            <div class="card-content">
                <p>{{ post.content|length > 150 ? post.content|slice(0, 150) ~ '...' : post.content }}</p>
            </div>

            <div class="card-actions">
                <a href="{{ base }}/post/show?id={{ post.id }}" class="btn primary">👁️ {{t('posts.view')}}</a>
                {% if logged_in %}
                <a href="{{ base }}/post/edit?id={{ post.id }}" class="btn">✏️ {{t('posts.edit')}}</a>
                <form action="{{ base }}/post/delete" method="post" style="display: inline;">
                    <input type="hidden" name="id" value="{{ post.id }}">
                    <button type="submit" class="btn red" onclick="return confirm('Are you sure you want to delete this post?')">🗑️ {{t('posts.delete')}}</button>
                </form>
                {% endif %}
            </div>
        </article>
        {% endfor %}
    </div>
    {% else %}
    <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);">
        <div style="font-size: 4em; margin-bottom: 20px; opacity: 0.3;">📝</div>
        <h3 style="color: #666; margin-bottom: 15px;">No Posts Yet</h3>
        <p style="color: #999; margin-bottom: 30px; font-size: 1.1em;">
            This category doesn't have any posts yet. Be the first to create one!
        </p>
        {% if logged_in %}
        <a href="{{ base }}/post/create" class="btn primary">✍️ Create First Post</a>
        {% else %}
        <p style="color: #999;">
            <a href="{{ base }}/login" style="color: #3498db; text-decoration: none; font-weight: bold;">Login</a>
            to create posts in this category.
        </p>
        {% endif %}
    </div>
    {% endif %}
</div>
{{ include('layouts/footer.php')}}