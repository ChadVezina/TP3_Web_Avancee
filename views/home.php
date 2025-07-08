{{ include('layouts/header.php', {title: t('page.home')})}}

<!-- Hero Section -->
<section class="hero">
   <div class="container">
      <div class="hero-content">
         <h1 class="hero-title">
            {{ t('misc.welcome') }} {{ t('misc.site_title') }} <span class="text-primary">{{ t('misc.site_title') }}</span>
         </h1>
         <p class="hero-subtitle">
            {{ t('hero.subtitle') }}
         </p>
         <div class="hero-actions">
            {% if logged_in %}
            <a href="{{ lang_url('post/create') }}" class="btn-solid">✍️ {{ t('btn.create') }}</a>
            <a href="{{ lang_url('posts') }}" class="btn-outline">📚 {{ t('nav.posts') }}</a>
            {% else %}
            <a href="{{ lang_url('user/create') }}" class="btn-solid">🎉 {{ t('nav.register') }}</a>
            <a href="{{ lang_url('posts') }}" class="btn-outline">📚 {{ t('nav.posts') }}</a>
            {% endif %}
         </div>
      </div>
   </div>
</section>

<!-- Statistics Section -->
<section class="stats">
   <div class="container">
      <h2 class="section-title">
         {{ t('stats.our_community') }}
      </h2>
      <div class="stats-grid">
         <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-number">{{ totalPosts }}</div>
            <div class="stat-label">{{ t('stats.published_articles') }}</div>
         </div>
         <div class="stat-card">
            <div class="stat-icon">📁</div>
            <div class="stat-number">{{ totalCategories }}</div>
            <div class="stat-label">{{ t('stats.categories') }}</div>
         </div>
         <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-number">{{ totalUsers }}</div>
            <div class="stat-label">{{ t('stats.authors') }}</div>
         </div>
      </div>
   </div>
</section>

<!-- Categories Section -->
{% if categories %}
<section class="categories-preview">
   <div class="container">
      <h2 class="section-title">
         {{ t('nav.home_categories') }}
      </h2>
      <div class="categories-grid">
         {% for category in categories %}
         <a href="{{base}}/posts?category={{ category.id }}" class="category-card">
            <span class="category-icon">📂</span>
            <span class="category-name">{{ category.name }}</span>
         </a>
         {% endfor %}
      </div>
   </div>
</section>
{% endif %}

<!-- Recent Posts Section -->
{% if recentPosts %}
<section class="recent-posts">
   <div class="container">
      <div class="section-header">
         <h2 class="section-title">
            {{ t('recent_posts.title') }}
         </h2>
         <a href="{{base}}/posts" class="btn-outline">{{ t('recent_posts.view_all')}}</a>
      </div>
      <div class="posts-grid">
         {% for post in recentPosts %}
         <article class="post-card">
            <div class="post-meta">
               <span class="post-category">{{ post.category_name }}</span>
               <span class="post-date">{{ post.created_at|date('d/m/Y') }}</span>
            </div>
            <h3 class="post-title">
               <a href="{{base}}/post/{{ post.id }}">{{ post.title }}</a>
            </h3>
            <p class="post-excerpt">
               {{ post.content|slice(0, 150) }}{% if post.content|length > 150 %}...{% endif %}
            </p>
            <div class="post-author">
               <span class="author-avatar">{{ post.author_name|slice(0, 1)|upper }}</span>
               <span class="author-name">{{ post.author_name }}</span>
            </div>
         </article>
         {% endfor %}
      </div>
   </div>
</section>
{% endif %}

<!-- Call to Action Section -->
{% if not logged_in %}
<section class="cta">
   <div class="container">
      <div class="cta-content">
         <h2 class="cta-title">
            {{ t('cta.title') }}
         </h2>
         <p class="cta-subtitle">
            {{ t('cta.subtitle') }}
         </p>
         <div class="cta-actions">
            <a href="{{base}}/user/create" class="btn-solid">🎉 {{t('cta.create_account')}}</a>
            <a href="{{base}}/login" class="btn-outline">🔐 {{t('cta.login')}}</a>
         </div>
      </div>
   </div>
</section>
{% endif %}

{{ include('layouts/footer.php')}}