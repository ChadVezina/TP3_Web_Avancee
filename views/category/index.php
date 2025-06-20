{{ include('layouts/header.php', {title: 'Categories'})}}
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="color: #333; margin: 0;">Categories</h1>
        {% if logged_in %}
        <a href="{{ base }}/category/create" class="btn primary">➕ New Category</a>
        {% endif %}
    </div>

    {% if categories %}
    <div class="cards-container single-column">
        {% for category in categories %}
        <article class="card">
            <div class="card-header">
                <h2 class="card-title">📁 {{ category.name }}</h2>
            </div>

            <div class="card-actions">
                <a href="{{ base }}/category/show?id={{ category.id }}" class="btn primary">👁️ View Posts</a>
                {% if logged_in %}
                <a href="{{ base }}/category/edit?id={{ category.id }}" class="btn">✏️ Edit</a>
                <form action="{{ base }}/category/delete" method="post" style="display: inline;">
                    <input type="hidden" name="id" value="{{ category.id }}">
                    <button type="submit" class="btn red">🗑️ Delete</button>
                </form>
                {% endif %}
            </div>
        </article>
        {% endfor %}
    </div>
    {% else %}
    <div style="text-align: center; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h3 style="color: #666;">No categories available</h3>
        <p style="color: #999;">Create your first category to organize your posts!</p>
        {% if logged_in %}
        <a href="{{ base }}/category/create" class="btn primary">Create First Category</a>
        {% endif %}
    </div>
    {% endif %}
</div>
{{ include('layouts/footer.php')}}