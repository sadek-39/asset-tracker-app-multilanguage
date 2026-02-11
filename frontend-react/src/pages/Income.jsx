import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../services/api';
import { Wallet, Plus, Trash2, Building2, CreditCard, Landmark, CircleDollarSign } from 'lucide-react';

const Income = () => {
    const queryClient = useQueryClient();
    const [isAddSourceOpen, setIsAddSourceOpen] = useState(false);
    const [isAddIncomeOpen, setIsAddIncomeOpen] = useState(false);

    const { data: sources } = useQuery({
        queryKey: ['income-sources'],
        queryFn: () => api.get('/income-sources').then(res => res.data)
    });

    const { data: transactions } = useQuery({
        queryKey: ['income-transactions'],
        queryFn: () => api.get('/income-transactions').then(res => res.data)
    });

    const addSourceMutation = useMutation({
        mutationFn: (newSource) => api.post('/income-sources', newSource),
        onSuccess: () => {
            queryClient.invalidateQueries(['income-sources']);
            setIsAddSourceOpen(false);
        }
    });

    const addIncomeMutation = useMutation({
        mutationFn: (newIncome) => api.post('/income-transactions', newIncome),
        onSuccess: () => {
            queryClient.invalidateQueries(['income-transactions', 'income-sources', 'summary']);
            setIsAddIncomeOpen(false);
        }
    });

    const deleteTxMutation = useMutation({
        mutationFn: (id) => api.delete(`/income-transactions/${id}`),
        onSuccess: () => queryClient.invalidateQueries(['income-transactions', 'income-sources', 'summary'])
    });

    const sourceIcons = {
        bank_account: Landmark,
        cash: CircleDollarSign,
        digital_wallet: CreditCard,
        other: Wallet
    };

    return (
        <div className="space-y-8">
            <header className="flex justify-between items-end">
                <div>
                    <h1 className="text-3xl font-bold">Income Management</h1>
                    <p className="text-slate-500 mt-1">Manage your income sources and track transactions</p>
                </div>
                <div className="flex gap-3">
                    <button
                        onClick={() => setIsAddSourceOpen(true)}
                        className="btn-secondary"
                    >
                        New Source
                    </button>
                    <button
                        onClick={() => setIsAddIncomeOpen(true)}
                        className="btn-primary"
                    >
                        Add Income
                    </button>
                </div>
            </header>

            {/* Income Sources Grid */}
            <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                {sources?.map((source) => {
                    const Icon = sourceIcons[source.source_type] || Wallet;
                    return (
                        <div key={source.id} className="bg-slate-900 border border-slate-800 p-5 rounded-2xl relative overflow-hidden group">
                            <div className="absolute top-0 right-0 p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button className="text-slate-500 hover:text-red-500"><Trash2 size={16} /></button>
                            </div>
                            <div className="p-2 w-10 h-10 bg-blue-500/10 text-blue-500 rounded-lg mb-4">
                                <Icon size={24} />
                            </div>
                            <h4 className="font-bold text-slate-300">{source.source_name}</h4>
                            <p className="text-xl font-bold mt-1">${source.current_balance?.toLocaleString()}</p>
                        </div>
                    );
                })}
            </div>

            {/* Transactions List */}
            <div className="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden">
                <div className="p-6 border-b border-slate-800 flex justify-between items-center">
                    <h2 className="text-xl font-bold">Income History</h2>
                </div>
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-slate-800/50 text-slate-500 text-xs uppercase">
                            <tr>
                                <th className="px-6 py-4">Date</th>
                                <th className="px-6 py-4">Description</th>
                                <th className="px-6 py-4">Source</th>
                                <th className="px-6 py-4">Amount</th>
                                <th className="px-6 py-4">Action</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800">
                            {transactions?.map((tx) => (
                                <tr key={tx.id} className="hover:bg-slate-800/30 transition-colors">
                                    <td className="px-6 py-4 text-sm">{new Date(tx.transaction_date).toLocaleDateString()}</td>
                                    <td className="px-6 py-4 font-medium">{tx.description}</td>
                                    <td className="px-6 py-4 text-slate-400 capitalize">{sources?.find(s => s.id === tx.source_id)?.source_name}</td>
                                    <td className="px-6 py-4 font-bold text-green-500">+${tx.amount.toLocaleString()}</td>
                                    <td className="px-6 py-4">
                                        <button
                                            onClick={() => deleteTxMutation.mutate(tx.id)}
                                            className="text-slate-500 hover:text-red-500 transition-colors"
                                        >
                                            <Trash2 size={18} />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Simplified Modals - In a real app these would be separate components */}
            {isAddIncomeOpen && (
                <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div className="bg-slate-900 border border-slate-800 p-8 rounded-3xl w-full max-w-md">
                        <h2 className="text-2xl font-bold mb-6">Add Income</h2>
                        <form onSubmit={(e) => {
                            e.preventDefault();
                            const formData = new FormData(e.target);
                            addIncomeMutation.mutate({
                                source_id: parseInt(formData.get('source_id')),
                                amount: parseFloat(formData.get('amount')),
                                description: formData.get('description'),
                                transaction_date: formData.get('date'),
                            });
                        }} className="space-y-4">
                            <select name="source_id" className="input-field" required>
                                <option value="">Select Source</option>
                                {sources?.map(s => <option key={s.id} value={s.id}>{s.source_name}</option>)}
                            </select>
                            <input name="amount" type="number" step="0.01" placeholder="Amount" className="input-field" required />
                            <input name="description" placeholder="Description" className="input-field" required />
                            <input name="date" type="date" className="input-field" required />
                            <div className="flex gap-4 mt-6">
                                <button type="button" onClick={() => setIsAddIncomeOpen(false)} className="btn-secondary w-full">Cancel</button>
                                <button type="submit" className="btn-primary w-full">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Income;
