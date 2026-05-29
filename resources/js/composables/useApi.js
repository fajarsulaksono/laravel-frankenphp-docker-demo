const BASE = '/api'

async function request(url, options = {}) {
    const res = await fetch(`${BASE}${url}`, {
        headers: { 'Content-Type': 'application/json', Accept: 'application/json', ...options.headers },
        ...options,
    })
    if (!res.ok) {
        const err = await res.json().catch(() => ({ message: res.statusText }))
        throw new Error(err.message || `HTTP ${res.status}`)
    }
    return res.json()
}

export function useApi() {
    return {
        get: (url) => request(url),
        post: (url, data) => request(url, { method: 'POST', body: JSON.stringify(data) }),
        delete: (url) => request(url, { method: 'DELETE' }),
    }
}
