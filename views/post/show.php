{{ include('layouts/header.php', {title: 'View Post'})}}
<div style="max-width: 800px; margin: 0 auto;">
    <!-- Post Card -->
    <article class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h1 class="card-title" style="font-size: 2em; margin-bottom: 15px;">{{ post.title }}</h1>
            <div class="card-meta">
                <div class="card-meta-item">
                    <span class="card-badge category">📁 {{ post.category_name ?? 'No Category' }}</span>
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
            <div style="line-height: 1.8; font-size: 1.1em;">
                {{ post.content|nl2br }}
            </div>
        </div>

        <div class="card-actions">
            {% if logged_in %}
            <a href="{{ base }}/post/edit?id={{ post.id }}" class="btn primary">✏️ Edit Post</a>
            {% endif %}
            <a href="{{ base }}/posts" class="btn">← Back to Posts</a>
        </div>
    </article>

    <!-- Comments Section -->
    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0;">💬 Comments ({{ comments|length }})</h3>
        </div>

        <div class="card-content">
            {% if comments %}
            <div style="display: flex; flex-direction: column; gap: 15px;">
                {% for comment in comments %}
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #3498db;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <div>
                            <strong style="color: #333;">{{ comment.author_name ?? 'Unknown User' }}</strong>
                            <span style="color: #666; font-size: 0.9em; margin-left: 10px;">{{ comment.created_at }}</span>
                        </div>
                        {% if logged_in and auth_user.id == comment.user_id %}
                        <form action="{{ base }}/comment/delete" method="post" style="margin: 0;">
                            <input type="hidden" name="id" value="{{ comment.id }}">
                            <input type="hidden" name="post_id" value="{{ post.id }}">
                            <button type="submit" class="btn red" style="padding: 4px 8px; font-size: 0.8em;" onclick="return confirm('Are you sure you want to delete this comment?')">🗑️</button>
                        </form>
                        {% endif %}
                    </div>
                    <p style="margin: 0; line-height: 1.6;">{{ comment.content }}</p>
                </div>
                {% endfor %}
            </div>
            {% else %}
            <p style="color: #666; font-style: italic; text-align: center; padding: 20px;">No comments yet. Be the first to comment!</p>
            {% endif %}

            {% if logged_in %}
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #333;">✍️ Add a Comment</h4>
                <form action="{{ base }}/comment/store" method="post">
                    <input type="hidden" name="post_id" value="{{ post.id }}">
                    <label style="display: block; margin-bottom: 10px;">
                        <textarea name="content" rows="4" placeholder="Write your comment here..." required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: Arial, sans-serif; resize: vertical;"></textarea>
                    </label>
                    <button type="submit" class="btn primary">💬 Post Comment</button>
                </form>
            </div>
            {% else %}
            <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 8px; border: 1px solid #ffeaa7; text-align: center;">
                <p style="margin: 0; color: #856404;">Please <a href="{{ base }}/login" style="color: #3498db; text-decoration: none; font-weight: bold;">login</a> to post a comment.</p>
            </div>
            {% endif %}
        </div>
    </div>
</div>
{{ include('layouts/footer.php')}}