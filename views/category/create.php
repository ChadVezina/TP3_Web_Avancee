{{ include('layouts/header.php', {title: 'Create Category'})}}
<div class="form-container">
    <div class="form-header">
        <div class="icon">📁</div>
        <h1>Create New Category</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9; font-weight: 300;">Add a new category to organize posts</p>
    </div>

    <div class="form-body">
        <form action="{{base}}/category/store" method="post">
            <div class="form-group">
                <label for="name">🏷️ Category Name</label>
                <input type="text" id="name" name="name" value="{{category.name}}" placeholder="Enter category name (e.g., Technology, Travel, Food)" required>
                {% if errors.name is defined %}
                <span class="field-error">{{errors.name}}</span>
                {% endif %}
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary">✨ Create Category</button>
                <a href="{{ base }}/categories" class="btn secondary">❌ Cancel</a>
            </div>
        </form>
    </div>
</div>
{{ include('layouts/footer.php')}}