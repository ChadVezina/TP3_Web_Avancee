{{ include('layouts/header.php', {title: 'Home'})}}
 <div class="container">
    <h3> Bonjour {{ session["username"] }} ! </h3>
    email: {{ session["email"] }}<br>
    en ligne: {{ logged_in ? "oui" : "non" }}<br>
    privilege_id: {{ session["privilege_id"] }}<br>
    fingerPrint: {{ session["fingerPrint"] }}<br>
    user_id: {{ session["user_id"] }}<br>
 </div>
{{ include('layouts/footer.php')}}