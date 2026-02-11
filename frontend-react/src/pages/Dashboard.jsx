import React from 'react';
import { useQuery } from '@tanstack/react-query';
import api from '../services/api';
import {
    TrendingUp,
    TrendingDown,
    PiggyBank,
    AccountBook,
    Plus,
    Calendar,
    ArrowRight
} from 'lucide-react';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    ArcElement
} from 'chart.js';
import { Line, Doughnut } from 'react-chartjs-2';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
);

const Dashboard = () => {
    const { data: summary, isLoading: summaryLoading } = useQuery({
        queryKey: ['summary'],
        queryFn: () => api.get('/dashboard/summary').then(res => res.data)
    });

    const { data: transactions, isLoading: txLoading } = useQuery({
        queryKey: ['transactions'],
        queryFn: async () => {
            const [income, expenses] = await Promise.all([
                api.get('/income-transactions'),
                api.get('/expense-transactions')
            ]);
            return [...income.data.map(t => ({ ...t, type: 'income' })),
            ...expenses.data.map(t => ({ ...t, type: 'expense' }))]
                .sort((a, b) => new Date(b.transaction_date) - new Date(a.transaction_date))
                .slice(0, 10);
        }
    });

    if (summaryLoading || txLoading) return <div className="animate-pulse space-y-8">
        <div className="h-40 bg-slate-900 rounded-3xl"></div>
        <div className="grid grid-cols-4 gap-6">
            {[1, 2, 3, 4].map(i => <div key={i} className="h-32 bg-slate-900 rounded-2xl"></div>)}
        </div>
    </div>;

    const stats = [
        { label: 'Monthly Income', value: summary?.total_income, icon: TrendingUp, color: 'text-green-500', bg: 'bg-green-500/10' },
        { label: 'Monthly Expenses', value: summary?.total_expenses, icon: TrendingDown, color: 'text-red-500', bg: 'bg-red-500/10' },
        { label: 'Net Savings', value: summary?.net_savings, icon: PiggyBank, color: 'text-blue-500', bg: 'bg-blue-500/10' },
        { label: 'Total Assets', value: summary?.total_assets_value, icon: AccountBook, color: 'text-purple-500', bg: 'bg-purple-500/10' },
    ];

    return (
        <div className="space-y-8">
            <header className="flex justify-between items-end">
                <div>
                    <h1 className="text-3xl font-bold bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">Dashboard</h1>
                    <p className="text-slate-500 flex items-center gap-2 mt-1">
                        <Calendar size={14} /> {new Date().toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}
                    </p>
                </div>
                <div className="flex gap-3">
                    <button className="flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl transition-all font-medium border border-slate-700">
                        <Plus size={18} /> Add Income
                    </button>
                    <button className="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl transition-all font-medium text-white shadow-lg shadow-blue-500/25">
                        <Plus size={18} /> Record Expense
                    </button>
                </div>
            </header>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {stats.map((stat, idx) => (
                    <div key={idx} className="bg-slate-900 border border-slate-800 p-6 rounded-2xl hover:border-slate-700 transition-all group">
                        <div className="flex justify-between items-start mb-4">
                            <div className={`p-3 rounded-xl ${stat.bg} ${stat.color} group-hover:scale-110 transition-transform`}>
                                <stat.icon size={24} />
                            </div>
                        </div>
                        <p className="text-slate-500 text-sm font-medium">{stat.label}</p>
                        <h3 className="text-2xl font-bold mt-1">${stat.value?.toLocaleString() || '0.00'}</h3>
                    </div>
                ))}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Recent Transactions */}
                <div className="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-3xl p-6">
                    <div className="flex justify-between items-center mb-6">
                        <h2 className="text-xl font-bold">Recent Activity</h2>
                        <button className="text-blue-500 text-sm font-bold flex items-center gap-1 hover:gap-2 transition-all">
                            View All <ArrowRight size={14} />
                        </button>
                    </div>
                    <div className="space-y-4">
                        {transactions?.map((tx, idx) => (
                            <div key={idx} className="flex items-center justify-between p-4 hover:bg-slate-800/50 rounded-2xl transition-all border border-transparent hover:border-slate-800">
                                <div className="flex items-center gap-4">
                                    <div className={`p-3 rounded-xl ${tx.type === 'income' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500'}`}>
                                        {tx.type === 'income' ? <TrendingUp size={18} /> : <TrendingDown size={18} />}
                                    </div>
                                    <div>
                                        <p className="font-bold">{tx.description || tx.category}</p>
                                        <p className="text-xs text-slate-500">{new Date(tx.transaction_date).toLocaleDateString()}</p>
                                    </div>
                                </div>
                                <span className={`font-bold ${tx.type === 'income' ? 'text-green-500' : 'text-slate-200'}`}>
                                    {tx.type === 'income' ? '+' : '-'}${tx.amount.toLocaleString()}
                                </span>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Expense Breakdown / Caching placeholder */}
                <div className="bg-slate-900 border border-slate-800 rounded-3xl p-6">
                    <h2 className="text-xl font-bold mb-6">Expense Breakdown</h2>
                    <div className="aspect-square flex items-center justify-center text-slate-500">
                        <Doughnut
                            data={{
                                labels: ['Food', 'Rent', 'Transport', 'Utilities', 'Others'],
                                datasets: [{
                                    data: [12, 19, 3, 5, 2],
                                    backgroundColor: [
                                        'rgba(59, 130, 246, 0.5)',
                                        'rgba(16, 185, 129, 0.5)',
                                        'rgba(245, 158, 11, 0.5)',
                                        'rgba(239, 68, 68, 0.5)',
                                        'rgba(139, 92, 246, 0.5)',
                                    ],
                                    borderColor: 'rgba(255, 255, 255, 0.1)',
                                    borderWidth: 1,
                                }]
                            }}
                            options={{ plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8' } } } }}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Dashboard;
