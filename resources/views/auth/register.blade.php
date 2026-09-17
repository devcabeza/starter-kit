<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
    <h1>Register</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">Name</label>
        <input id="name" name="name" type="text" />
        <label for="email">Email</label>
        <input id="email" name="email" type="email" />
        <label for="password">Password</label>
        <input id="password" name="password" type="password" />
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" />
        <button type="submit">Register</button>
    </form>
</body>
</html>
