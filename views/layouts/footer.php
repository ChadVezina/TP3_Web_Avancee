</main>

<footer>
    <div class="footer-content">
        <div class="footer-row">
            <div class="footer-section">
                <h3>🔗 Quick Links:</h3>
            </div>
            <div class="footer-links">
                <a href="{{base}}/posts">📚 All Posts</a>
                <a href="{{base}}/categories">📁 Categories</a>
                {% if logged_in %}
                <a href="{{base}}/post/create">✍️ Write Post</a>
                <a href="{{base}}/category/create">➕ New Category</a>
                {% else %}
                <a href="{{base}}/login">🔐 Login</a>
                <a href="{{base}}/user/create">🎉 Join Community</a>
                {% endif %}
            </div>
        </div>
        <div class="footer-row">
            <div class="footer-section">
                <h3>💡 Features:</h3>
            </div>
            <div class="footer-features">
                <span class="footer-feature">🎨 Beautiful Design</span>
                <span class="footer-feature">📱 Mobile Friendly</span>
                <span class="footer-feature">💬 Comment System</span>
                <span class="footer-feature">🔒 Secure Platform</span>
                <span class="footer-feature">⚡ Fast Performance</span>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>
            © 2025 Chad Vézina • The Modern Blogger Platform • Made with <span class="heart">❤️</span> for the community
        </p>
    </div>
</footer>
</body>

</html>