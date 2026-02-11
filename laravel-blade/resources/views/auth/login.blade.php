<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AssetTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #0f172a; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-slate-900 border border-slate-800 p-10 rounded-3xl shadow-2xl">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-white">AssetTracker</h1>
            <p class="text-slate-500 mt-2 font-medium">Session-based Authentication (Laravel)</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-4 rounded-xl text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif
            <div>
                <label class="block text-sm font-bold text-slate-400 mb-2">Email Address</label>
                <input type="email" name="email" required class="w-full px-5 py-4 bg-slate-950 border border-slate-800 rounded-2xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="name@example.com">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-400 mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-5 py-4 bg-slate-950 border border-slate-800 rounded-2xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all" placeholder="••••••••">
            </div>
            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 transition-all">Sign In</button>
            <p class="text-center text-slate-500 text-sm font-medium">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register</a>
            </p>
        </form>
    </div>
</body>
</html>
