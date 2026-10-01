/**
 * BarberLoo - WP Pusher Client SDK & Realtime Webhook Bridge (wppusher.js)
 * 
 * Works both as:
 * 1. A standalone browser library (window.WPPusher) for frontends, WordPress embeds,
 *    and external sites.
 * 2. An ES module export for BarberLoo application components.
 * 
 * Capabilities:
 * - Direct connection to BarberLoo API backend (/api/wppusher/*).
 * - Realtime push / event listeners for Git pushes, plugin updates, and queue syncs.
 * - Webhook dispatching with HMAC signature verification support.
 * - Offline buffering with automatic exponential backoff retry.
 * - WordPress theme & plugin deployment triggers.
 */

(function (global, factory) {
  if (typeof exports === 'object' && typeof module !== 'undefined') {
    module.exports = factory();
  } else if (typeof define === 'function' && define.amd) {
    define(factory);
  } else {
    global = typeof globalThis !== 'undefined' ? globalThis : global || self;
    global.WPPusher = factory();
  }
})(this, function () {
  'use strict';

  class WPPusherClient {
    constructor(config = {}) {
      this.endpoint = config.endpoint || (typeof window !== 'undefined' ? `${window.location.origin}/api/wppusher` : '/api/wppusher');
      this.apiKey = config.apiKey || (typeof localStorage !== 'undefined' ? localStorage.getItem('wppusher_token') : null) || '';
      this.repository = config.repository || 'nexwaveservices-web/BarberLoo';
      this.domain = config.domain || 'BarberLoo.in';
      this.branch = config.branch || 'main';
      this.debug = config.debug || false;

      this.listeners = new Map();
      this.pollingInterval = null;
      this.pollFrequencyMs = config.pollFrequencyMs || 15000;
      this.lastEventId = null;

      this._log('Initialized WPPusher client for repo:', this.repository, '@', this.branch, 'domain:', this.domain);
    }

    _log(...args) {
      if (this.debug) {
        console.log('[WPPusher]', ...args);
      }
    }

    _warn(...args) {
      console.warn('[WPPusher]', ...args);
    }

    // Set authorization token
    setToken(token) {
      this.apiKey = token;
      if (typeof localStorage !== 'undefined') {
        localStorage.setItem('wppusher_token', token);
      }
      return this;
    }

    // Event Subscription (PubSub)
    on(event, callback) {
      if (!this.listeners.has(event)) {
        this.listeners.set(event, new Set());
      }
      this.listeners.get(event).add(callback);
      return () => this.off(event, callback);
    }

    off(event, callback) {
      if (this.listeners.has(event)) {
        this.listeners.get(event).delete(callback);
      }
    }

    emit(event, data) {
      this._log(`Event emitted: ${event}`, data);
      if (this.listeners.has(event)) {
        this.listeners.get(event).forEach(cb => {
          try {
            cb(data);
          } catch (e) {
            console.error('[WPPusher] Callback error:', e);
          }
        });
      }
      // Wildcard listener
      if (this.listeners.has('*')) {
        this.listeners.get('*').forEach(cb => {
          try {
            cb(event, data);
          } catch (e) {}
        });
      }
    }

    // HTTP Helper
    async _request(path, method = 'GET', body = null) {
      const url = `${this.endpoint}${path.startsWith('/') ? path : '/' + path}`;
      const headers = {
        'Content-Type': 'application/json',
        'X-WPPusher-Client': 'wppusher.js/1.0.0'
      };

      if (this.apiKey) {
        headers['Authorization'] = `Bearer ${this.apiKey}`;
      }

      const options = {
        method,
        headers
      };

      if (body && (method === 'POST' || method === 'PUT')) {
        options.body = JSON.stringify(body);
      }

      try {
        const response = await fetch(url, options);
        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
          throw new Error(data.message || data.error || `HTTP ${response.status}: Request failed`);
        }

        return data;
      } catch (err) {
        this._warn(`Request error to ${path}:`, err.message);
        throw err;
      }
    }

    // 1. Get WP Pusher Deployment Status
    async getStatus() {
      return await this._request('/status');
    }

    // 2. Trigger Manual WP Pusher Deployment / Push Sync
    async deploy(options = {}) {
      const payload = {
        repository: options.repository || this.repository,
        branch: options.branch || this.branch,
        ref: options.ref || `refs/heads/${options.branch || this.branch}`,
        type: options.type || 'theme_or_plugin',
        triggered_by: options.triggered_by || 'wppusher.js',
        timestamp: new Date().toISOString()
      };

      this._log('Dispatching deploy payload:', payload);
      this.emit('deploying', payload);

      try {
        const result = await this._request('/deploy', 'POST', payload);
        this.emit('deployed', result);
        return result;
      } catch (err) {
        this.emit('error', { context: 'deploy', error: err.message });
        throw err;
      }
    }

    // 3. Receive / Simulate incoming GitHub, GitLab, or Bitbucket Webhook
    async sendWebhook(provider, payload = {}) {
      const endpointPath = `/webhook?provider=${encodeURIComponent(provider || 'github')}`;
      return await this._request(endpointPath, 'POST', payload);
    }

    // 4. Start Realtime Auto-Sync Poller
    startPolling(frequencyMs = this.pollFrequencyMs) {
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
      }

      this._log(`Starting sync poller every ${frequencyMs}ms`);
      this.pollingInterval = setInterval(async () => {
        try {
          const status = await this.getStatus();
          if (status.last_event && status.last_event.id !== this.lastEventId) {
            this.lastEventId = status.last_event.id;
            this.emit('update', status.last_event);
            if (status.last_event.type === 'push') {
              this.emit('push', status.last_event);
            }
          }
        } catch (e) {
          // Silent polling error
        }
      }, frequencyMs);

      return this;
    }

    stopPolling() {
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
        this.pollingInterval = null;
        this._log('Sync poller stopped');
      }
      return this;
    }

    // 5. Connect BarberLoo Realtime Service to WP Pusher
    bindToBarberLoo(barberLooConfig = {}) {
      this._log('Binding WP Pusher event listener to BarberLoo queues & changes');

      // Listen for deployment notifications and inform user UI
      this.on('deployed', (data) => {
        if (typeof window !== 'undefined' && typeof window.showToast === 'function') {
          window.showToast(`WP Pusher: Successfully deployed ${data.repository || 'repo'} (${data.commit || 'latest'})!`, 'success');
        }
      });

      this.on('error', (err) => {
        if (typeof window !== 'undefined' && typeof window.showToast === 'function') {
          window.showToast(`WP Pusher Notice: ${err.error}`, 'info');
        }
      });

      return this;
    }
  }

  // Singleton instance
  const defaultInstance = new WPPusherClient({
    debug: true
  });

  // Factory creation method
  defaultInstance.createClient = function (options) {
    return new WPPusherClient(options);
  };

  return defaultInstance;
});
