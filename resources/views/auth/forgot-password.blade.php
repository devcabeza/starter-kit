<!DOCTYPE html>
<html>
<head><title>Forgot Password</title></head>
<body>
    <h1>Forgot Password</h1>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" type="email" />
        <button type="submit">Email Password Reset Link</button>
    </form>
</body>
</html>
