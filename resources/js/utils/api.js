import axios from 'axios';

const api = {
    loading: {},
    errors: {},

    async request(method, url, data = null, options = {}) {
        const key = options.loadingKey || url;
        this.loading[key] = true;
        this.errors[key] = null;

        try {
            const response = await axios({
                method,
                url: `/api/v1${url}`,
                data,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                ...options
            });
            return response.data;
        } catch (error) {
            this.errors[key] = error.response?.data?.message || 'An unexpected error occurred';
            throw error;
        } finally {
            this.loading[key] = false;
        }
    },

    isLoading(key) {
        return !!this.loading[key];
    },

    getError(key) {
        return this.errors[key];
    },

    clearError(key) {
        this.errors[key] = null;
    },

    // Convenience methods
    get(url, options = {}) {
        return this.request('GET', url, null, options);
    },

    post(url, data, options = {}) {
        return this.request('POST', url, data, options);
    },

    put(url, data, options = {}) {
        return this.request('PUT', url, data, options);
    },

    delete(url, options = {}) {
        return this.request('DELETE', url, null, options);
    }
}; 