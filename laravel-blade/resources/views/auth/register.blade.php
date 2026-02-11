<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AssetTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #0f172a; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-slate-900 border border-slate-800 p-10 rounded-3xl shadow-2xl">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-white">Create Account</h1>
            <p class="text-slate-500 mt-2 font-medium">Join AssetTracker today</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-400 mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full px-5 py-4 bg-slate-950 border border-slate-800 rounded-2xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-400 mb-2">Email Address</label>
                <input type="email" name="email" required class="w-full px-5 py-4 bg-slate-950 border border-slate-800 rounded-2xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-400 mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-5 py-4 bg-slate-950 border border-slate-800 rounded-2xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-400 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full px-5 py-4 bg-slate-950 border border-slate-800 rounded-2xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 transition-all">Register</button>
            <p class="text-center text-slate-500 text-sm font-medium">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login</a>
            </p>
        </form>
    </div>
</body>
</html>
