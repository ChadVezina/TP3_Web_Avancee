{{ include('layouts/header.php', {title: 'Error'})}}
 <div class="container">
    <h2>Error</h2>
    <strong class="error">{{ message }}</strong>
 </div>
{{ include('layouts/footer.php')}}