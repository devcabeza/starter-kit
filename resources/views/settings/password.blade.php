<!DOCTYPE html>
<html>
<head><title>Change Password</title></head>
<body>
    <h1>Change Password</h1>
    <form method="POST" action="{{ route('settings.password.update') }}">
        @csrf
        @method('PUT')
        <label for="current_password">Current Password</label>
        <input id="current_password" name="current_password" type="password" />
        <label for="password">New Password</label>
        <input id="password" name="password" type="password" />
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" />
        <button type="submit">Save</button>
    </form>
</body>
</html>
