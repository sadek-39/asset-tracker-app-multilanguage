import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../services/api';
import { Plus, Trash2, Building2, Wallet, Briefcase, Landmark } from 'lucide-react';

const Assets = () => {
    const queryClient = useQueryClient();
    const [isAddOpen, setIsAddOpen] = useState(false);

    const assetTypes = {
        dps: { label: 'DPS', icon: Landmark, color: 'text-blue-500' },
        bond: { label: 'Bond', icon: Building2, color: 'text-purple-500' },
        investment: { label: 'Investment', icon: Briefcase, color: 'text-amber-500' },
        other: { label: 'Other', icon: Wallet, color: 'text-slate-400' }
    };

    const { data: assets } = useQuery({
        queryKey: ['assets'],
        queryFn: () => api.get('/assets').then(res => res.data)
    });

    const addMutation = useMutation({
        mutationFn: (newAsset) => api.post('/assets', newAsset),
        onSuccess: () => {
            queryClient.invalidateQueries(['assets', 'summary']);
            setIsAddOpen(false);
        }
    });

    return (
        <div className="space-y-8">
            <header className="flex justify-between items-end">
                <div>
                    <h1 className="text-3xl font-bold">Assets Portolio</h1>
                    <p className="text-slate-500 mt-1">Track your long-term investments and savings</p>
                </div>
                <button
                    onClick={() => setIsAddOpen(true)}
                    className="btn-primary"
                >
                    <Plus size={18} /> New Asset
                </button>
            </header>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {assets?.map((asset) => {
                    const type = assetTypes[asset.asset_type] || assetTypes.other;
                    return (
                        <div key={asset.id} className="bg-slate-900 border border-slate-800 p-6 rounded-2xl relative group">
                            <div className="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button className="text-slate-500 hover:text-red-500"><Trash2 size={16} /></button>
                            </div>
                            <div className={`p-3 w-12 h-12 rounded-xl bg-slate-800 ${type.color} mb-4`}>
                                <type.icon size={24} />
                            </div>
                            <p className="text-xs font-bold uppercase text-slate-500 tracking-wider mb-1">{type.label}</p>
                            <h4 className="text-lg font-bold text-white mb-2">{asset.asset_name}</h4>
                            <p className="text-2xl font-bold text-blue-400">${asset.current_value?.toLocaleString()}</p>
                            {asset.institution_name && (
                                <p className="text-sm text-slate-500 mt-2 flex items-center gap-1">
                                    <Building2 size={12} /> {asset.institution_name}
                                </p>
                            )}
                        </div>
                    );
                })}
            </div>

            {isAddOpen && (
                <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div className="bg-slate-900 border border-slate-800 p-8 rounded-3xl w-full max-w-md">
                        <h2 className="text-2xl font-bold mb-6">Track New Asset</h2>
                        <form onSubmit={(e) => {
                            e.preventDefault();
                            const formData = new FormData(e.target);
                            addMutation.mutate({
                                asset_type: formData.get('asset_type'),
                                asset_name: formData.get('asset_name'),
                                current_value: parseFloat(formData.get('current_value')),
                                institution_name: formData.get('institution_name'),
                                description: formData.get('description'),
                            });
                        }} className="space-y-4">
                            <select name="asset_type" className="input-field" required>
                                <option value="">Select Asset Type</option>
                                {Object.entries(assetTypes).map(([key, val]) => (
                                    <option key={key} value={key}>{val.label}</option>
                                ))}
                            </select>
                            <input name="asset_name" placeholder="Asset Name (e.g. Sanchayapatra)" className="input-field" required />
                            <input name="current_value" type="number" step="0.01" placeholder="Current Value" className="input-field" required />
                            <input name="institution_name" placeholder="Institution Name (Optional)" className="input-field" />
                            <textarea name="description" placeholder="Description" className="input-field h-24" />
                            <div className="flex gap-4 mt-6">
                                <button type="button" onClick={() => setIsAddOpen(false)} className="btn-secondary w-full">Cancel</button>
                                <button type="submit" className="btn-primary w-full">Save Asset</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Assets;
