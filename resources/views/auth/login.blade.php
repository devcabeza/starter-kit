<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" type="email" />
        <label for="password">Password</label>
        <input id="password" name="password" type="password" />
        <button type="submit">Log in</button>
    </form>
</body>
</html>
