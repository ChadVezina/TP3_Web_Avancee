</main>

<footer>
    <div class="footer-content">
        <div class="footer-row">
            <div class="footer-section">
                <h3>🔗 {{t('footer.quick_links')}}</h3>
            </div>
            <div class="footer-links">
                <a href="{{base}}/posts">📚 {{t('posts.all')}}</a>
                <a href="{{base}}/categories">📁 {{t('categories.title')}}</a>
                {% if logged_in %}
                <a href="{{base}}/post/create">✍️ {{t('posts.create')}}</a>
                <a href="{{base}}/category/create">➕ {{t('categories.create')}}</a>
                {% else %}
                <a href="{{base}}/login">🔐 {{t('login.signin')}}</a>
                <a href="{{base}}/user/create">🎉 {{t('register.title')}}</a>
                {% endif %}
            </div>
        </div>
        <div class="footer-row">
            <div class="footer-section">
                <h3>💡 {{t('footer.features')}}</h3>
            </div>
            <div class="footer-features">
                <span class="footer-feature">🎨 {{t('footer.design')}}</span>
                <span class="footer-feature">📱 {{t('footer.mobile')}}</span>
                <span class="footer-feature">💬 {{t('footer.comments')}}</span>
                <span class="footer-feature">🔒 {{t('footer.secure')}}</span>
                <span class="footer-feature">⚡ {{t('footer.fast')}}</span>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>
            {{t('footer.copyright')}}
        </p>
    </div>
</footer>
</body>

</html>