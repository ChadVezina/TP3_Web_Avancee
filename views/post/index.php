{{ include('layouts/header.php', {title: 'Blog Posts'})}}
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="color: #333; margin: 0;">Blog Posts</h1>
        {% if logged_in %}
        <a href="{{ base }}/post/create" class="btn primary">✍️ New Post</a>
        {% endif %}
    </div>

    {% if posts %}
    <div class="cards-container">
        {% for post in posts %}
        <article class="card">
            <div class="card-header">
                <h2 class="card-title">{{ post.title }}</h2>
                <div class="card-meta">
                    <div class="card-meta-item">
                        <span class="card-badge category">{{ post.category_name ?? 'No Category' }}</span>
                    </div>
                    <div class="card-meta-item">
                        <span class="card-badge author">{{ post.author_name ?? 'Unknown Author' }}</span>
                    </div>
                    <div class="card-meta-item">
                        <span class="card-badge date">{{ post.created_at }}</span>
                    </div>
                </div>
            </div>

            <div class="card-content">
                <p>{{ post.content|length > 150 ? post.content|slice(0, 150) ~ '...' : post.content }}</p>
            </div>

            <div class="card-actions">
                <a href="{{ base }}/post/show?id={{ post.id }}" class="btn primary">👁️ View</a>
                {% if logged_in %}
                <a href="{{ base }}/post/edit?id={{ post.id }}" class="btn">✏️ Edit</a>
                <form action="{{ base }}/post/delete" method="post" style="display: inline;">
                    <input type="hidden" name="id" value="{{ post.id }}">
                    <button type="submit" class="btn red" onclick="return confirm('Are you sure you want to delete this post?')">🗑️ Delete</button>
                </form>
                {% endif %}
            </div>
        </article>
        {% endfor %}
    </div>
    {% else %}
    <div style="text-align: center; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h3 style="color: #666;">No posts available</h3>
        <p style="color: #999;">Be the first to create a post!</p>
        {% if logged_in %}
        <a href="{{ base }}/post/create" class="btn primary">Create First Post</a>
        {% endif %}
    </div>
    {% endif %}
</div>
{{ include('layouts/footer.php')}}