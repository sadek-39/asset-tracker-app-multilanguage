import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../services/api';
import { Trash2, Plus, Filter } from 'lucide-react';

const Expenses = () => {
    const queryClient = useQueryClient();
    const [isAddOpen, setIsAddOpen] = useState(false);

    const categories = [
        'food_dining', 'transportation', 'utilities', 'rent_mortgage',
        'healthcare_medical', 'entertainment', 'shopping_retail', 'education',
        'insurance', 'phone_internet', 'personal_care', 'savings_investments',
        'debt_payments', 'gifts_donations', 'miscellaneous'
    ];

    const { data: sources } = useQuery({
        queryKey: ['income-sources'],
        queryFn: () => api.get('/income-sources').then(res => res.data)
    });

    const { data: transactions } = useQuery({
        queryKey: ['expense-transactions'],
        queryFn: () => api.get('/expense-transactions').then(res => res.data)
    });

    const addMutation = useMutation({
        mutationFn: (newExpense) => api.post('/expense-transactions', newExpense),
        onSuccess: () => {
            queryClient.invalidateQueries(['expense-transactions', 'income-sources', 'summary']);
            setIsAddOpen(false);
        }
    });

    return (
        <div className="space-y-8">
            <header className="flex justify-between items-end">
                <div>
                    <h1 className="text-3xl font-bold">Expenses</h1>
                    <p className="text-slate-500 mt-1">Track your spending by category and source</p>
                </div>
                <button
                    onClick={() => setIsAddOpen(true)}
                    className="btn-primary"
                >
                    <Plus size={18} /> Record Expense
                </button>
            </header>

            <div className="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden">
                <div className="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-800/20">
                    <h2 className="text-xl font-bold">Expense History</h2>
                    <button className="flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors">
                        <Filter size={16} /> Filter
                    </button>
                </div>
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="text-slate-500 text-xs uppercase border-b border-slate-800">
                            <tr>
                                <th className="px-6 py-4 font-semibold">Date</th>
                                <th className="px-6 py-4 font-semibold">Category</th>
                                <th className="px-6 py-4 font-semibold">Description</th>
                                <th className="px-6 py-4 font-semibold">Paid From</th>
                                <th className="px-6 py-4 font-semibold">Amount</th>
                                <th className="px-6 py-4 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800">
                            {transactions?.map((tx) => (
                                <tr key={tx.id} className="hover:bg-slate-800/30">
                                    <td className="px-6 py-4 text-sm text-slate-400">{new Date(tx.transaction_date).toLocaleDateString()}</td>
                                    <td className="px-6 py-4">
                                        <span className="px-3 py-1 bg-slate-800 rounded-full text-xs font-bold capitalize text-slate-300">
                                            {tx.category.replace('_', ' ')}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 font-medium text-slate-200">{tx.description || '-'}</td>
                                    <td className="px-6 py-4 text-slate-400 capitalize">{sources?.find(s => s.id === tx.source_id)?.source_name}</td>
                                    <td className="px-6 py-4 font-bold text-slate-200">${tx.amount.toLocaleString()}</td>
                                    <td className="px-6 py-4">
                                        <button className="text-slate-500 hover:text-red-500"><Trash2 size={18} /></button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {isAddOpen && (
                <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div className="bg-slate-900 border border-slate-800 p-8 rounded-3xl w-full max-w-md animate-in fade-in zoom-in duration-200">
                        <h2 className="text-2xl font-bold mb-6">Record Expense</h2>
                        <form onSubmit={(e) => {
                            e.preventDefault();
                            const formData = new FormData(e.target);
                            addMutation.mutate({
                                source_id: parseInt(formData.get('source_id')),
                                amount: parseFloat(formData.get('amount')),
                                category: formData.get('category'),
                                description: formData.get('description'),
                                transaction_date: formData.get('date'),
                            });
                        }} className="space-y-4">
                            <select name="source_id" className="input-field" required>
                                <option value="">Select Source</option>
                                {sources?.map(s => <option key={s.id} value={s.id}>{s.source_name}</option>)}
                            </select>
                            <select name="category" className="input-field" required>
                                <option value="">Select Category</option>
                                {categories.map(cat => <option key={cat} value={cat}>{cat.replace('_', ' ')}</option>)}
                            </select>
                            <input name="amount" type="number" step="0.01" placeholder="Amount" className="input-field" required />
                            <input name="description" placeholder="Description (Optional)" className="input-field" />
                            <input name="date" type="date" className="input-field" required />
                            <div className="flex gap-4 mt-6">
                                <button type="button" onClick={() => setIsAddOpen(false)} className="btn-secondary w-full">Cancel</button>
                                <button type="submit" className="btn-primary w-full">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Expenses;
