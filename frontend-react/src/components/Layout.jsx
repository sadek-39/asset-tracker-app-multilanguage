import React from 'react';
import { useAuth } from '../context/AuthContext';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import {
    LayoutDashboard,
    ArrowUpCircle,
    ArrowDownCircle,
    Wallet,
    LogOut,
    Menu,
    X,
    Server
} from 'lucide-react';

const Layout = ({ children }) => {
    const { user, logout, backend, switchBackend } = useAuth();
    const [isMenuOpen, setIsMenuOpen] = React.useState(false);
    const navigate = useNavigate();
    const location = useLocation();

    const menuItems = [
        { name: 'Dashboard', icon: LayoutDashboard, path: '/' },
        { name: 'Income', icon: ArrowUpCircle, path: '/income' },
        { name: 'Expenses', icon: ArrowDownCircle, path: '/expenses' },
        { name: 'Assets', icon: Wallet, path: '/assets' },
    ];

    const handleLogout = () => {
        logout();
        navigate('/login');
    };

    return (
        <div className="flex h-screen bg-slate-950 text-slate-200">
            {/* Sidebar for desktop */}
            <aside className="hidden md:flex flex-col w-64 bg-slate-900 border-r border-slate-800">
                <div className="p-6">
                    <h1 className="text-2xl font-bold text-white flex items-center gap-2">
                        <Wallet className="text-blue-500" />
                        AssetTracker
                    </h1>
                </div>
                <nav className="flex-1 px-4 space-y-2">
                    {menuItems.map((item) => (
                        <Link
                            key={item.path}
                            to={item.path}
                            className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location.pathname === item.path
                                    ? 'bg-blue-600 text-white'
                                    : 'hover:bg-slate-800 text-slate-400'
                                }`}
                        >
                            <item.icon size={20} />
                            {item.name}
                        </Link>
                    ))}
                </nav>
                <div className="p-4 border-t border-slate-800 space-y-4">
                    <div className="flex items-center justify-between px-4 py-2 bg-slate-800 rounded-lg">
                        <span className="text-xs font-semibold uppercase text-slate-500 flex items-center gap-2">
                            <Server size={14} /> Backend
                        </span>
                        <select
                            value={backend}
                            onChange={(e) => switchBackend(e.target.value)}
                            className="bg-transparent text-xs font-bold text-blue-400 outline-none"
                        >
                            <option value="go">Go (Gin)</option>
                            <option value="java">Java (Boot)</option>
                        </select>
                    </div>
                    <button
                        onClick={handleLogout}
                        className="flex items-center gap-3 px-4 py-3 w-full text-slate-400 hover:text-red-400 transition-colors"
                    >
                        <LogOut size={20} />
                        Logout
                    </button>
                </div>
            </aside>

            {/* Main content */}
            <main className="flex-1 overflow-y-auto">
                {/* Mobile header */}
                <header className="md:hidden flex items-center justify-between p-4 bg-slate-900 border-b border-slate-800">
                    <h1 className="text-xl font-bold text-white">AssetTracker</h1>
                    <button onClick={() => setIsMenuOpen(!isMenuOpen)}>
                        {isMenuOpen ? <X /> : <Menu />}
                    </button>
                </header>

                <div className="p-4 md:p-8 max-w-7xl mx-auto">
                    {children}
                </div>
            </main>

            {/* Mobile menu overlay */}
            {isMenuOpen && (
                <div className="md:hidden fixed inset-0 z-50 bg-slate-950 flex flex-col">
                    <div className="p-4 flex justify-between">
                        <h1 className="text-xl font-bold">Menu</h1>
                        <button onClick={() => setIsMenuOpen(false)}><X /></button>
                    </div>
                    <nav className="flex-1 px-4 py-8 space-y-4">
                        {menuItems.map((item) => (
                            <Link
                                key={item.path}
                                to={item.path}
                                onClick={() => setIsMenuOpen(false)}
                                className="flex items-center gap-4 text-2xl font-medium"
                            >
                                <item.icon size={28} />
                                {item.name}
                            </Link>
                        ))}
                        <button
                            onClick={handleLogout}
                            className="flex items-center gap-4 text-2xl font-medium text-red-500 pt-8"
                        >
                            <LogOut size={28} />
                            Logout
                        </button>
                    </nav>
                </div>
            )}
        </div>
    );
};

export default Layout;
