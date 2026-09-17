<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
    <h1>Reset Password</h1>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ $email ?? '' }}" />
        <label for="password">Password</label>
        <input id="password" name="password" type="password" />
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" />
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
