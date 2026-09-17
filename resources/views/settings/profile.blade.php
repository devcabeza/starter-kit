<!DOCTYPE html>
<html>
<head><title>Profile Settings</title></head>
<body>
    <h1>Profile Settings</h1>
    <form method="POST" action="{{ route('settings.profile.update') }}">
        @csrf
        @method('PUT')
        <label for="name">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}" />
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" />
        <button type="submit">Save</button>
    </form>
</body>
</html>
