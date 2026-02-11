import React, { createContext, useContext, useState, useEffect } from 'react';
import api, { setBackend } from '../services/api';

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [backend, setBackendState] = useState(localStorage.getItem('backend_type') || 'go');

    useEffect(() => {
        const token = localStorage.getItem('token');
        const storedUser = localStorage.getItem('user');
        if (token && storedUser) {
            setUser(JSON.parse(storedUser));
        }
        setLoading(false);
    }, []);

    const login = async (email, password) => {
        const response = await api.post('/login', { email, password });
        const { token, user } = response.data;
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));
        setUser(user);
    };

    const logout = () => {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        setUser(null);
    };

    const switchBackend = (type) => {
        const url = type === 'go' ? 'http://localhost:8080/api' : 'http://localhost:8085/api';
        setBackend(url);
        setBackendState(type);
        localStorage.setItem('backend_type', type);
        logout(); // Force log out on backend switch to ensure session consistency
    };

    return (
        <AuthContext.Provider value={{ user, login, logout, backend, switchBackend, loading }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => useContext(AuthContext);
