import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Wallet, Server } from 'lucide-react';

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const { login, backend, switchBackend } = useAuth();
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await login(email, password);
            navigate('/');
        } catch (err) {
            setError(err.response?.data?.error || 'Failed to login');
        }
    };

    return (
        <div className="min-h-screen bg-slate-950 flex items-center justify-center p-4">
            <div className="max-w-md w-full space-y-8 bg-slate-900 p-8 rounded-2xl border border-slate-800">
                <div className="text-center">
                    <div className="flex justify-center mb-4">
                        <div className="p-3 bg-blue-600 rounded-xl shadow-lg shadow-blue-500/20">
                            <Wallet size={32} className="text-white" />
                        </div>
                    </div>
                    <h2 className="text-3xl font-extrabold text-white">Welcome back</h2>
                    <p className="mt-2 text-slate-400">Sign in to your account</p>
                </div>

                <div className="p-4 bg-slate-800/50 rounded-xl border border-slate-700 flex items-center justify-between">
                    <span className="text-sm font-medium text-slate-300 flex items-center gap-2">
                        <Server size={16} className="text-blue-400" /> Backend Stack
                    </span>
                    <select
                        value={backend}
                        onChange={(e) => switchBackend(e.target.value)}
                        className="bg-slate-900 border border-slate-700 rounded-md px-2 py-1 text-xs font-bold text-blue-400 outline-none"
                    >
                        <option value="go">Golang Gin</option>
                        <option value="java">Spring Boot</option>
                    </select>
                </div>

                <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
                    {error && (
                        <div className="bg-red-500/10 border border-red-500/50 text-red-500 px-4 py-2 rounded-lg text-sm">
                            {error}
                        </div>
                    )}
                    <div className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-slate-300">Email address</label>
                            <input
                                type="email"
                                required
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                className="mt-1 block w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="name@example.com"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-slate-300">Password</label>
                            <input
                                type="password"
                                required
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                className="mt-1 block w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="••••••••"
                            />
                        </div>
                    </div>

                    <button
                        type="submit"
                        className="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all"
                    >
                        Sign in
                    </button>

                    <div className="text-center">
                        <p className="text-sm text-slate-400">
                            Don't have an account?{' '}
                            <Link to="/register" className="font-bold text-blue-500 hover:text-blue-400 transition-colors">
                                Register here
                            </Link>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default Login;
