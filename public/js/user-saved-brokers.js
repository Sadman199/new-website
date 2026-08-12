(function () {
    'use strict';

    var body = document.body;
    if (!body || body.dataset.userAuth !== '1') {
        return;
    }

    var syncUrl = body.dataset.savedSyncUrl;
    var indexUrl = body.dataset.savedIndexUrl;
    var storageKey = 'savedBrokers';
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var token = csrf ? csrf.getAttribute('content') : '';

    function readLocal() {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || '[]').map(String);
        } catch (e) {
            return [];
        }
    }

    function writeLocal(ids) {
        localStorage.setItem(storageKey, JSON.stringify(ids.map(String)));
    }

    function mergeIds(serverIds, localIds) {
        var merged = {};
        serverIds.concat(localIds).forEach(function (id) {
            if (id) {
                merged[String(id)] = true;
            }
        });
        return Object.keys(merged);
    }

    function syncSavedBrokers() {
        if (!syncUrl || !indexUrl) {
            return;
        }

        fetch(indexUrl, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        })
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('Failed to load saved brokers');
                }
                return res.json();
            })
            .then(function (data) {
                var serverIds = (data.broker_ids || []).map(String);
                var localIds = readLocal();
                var merged = mergeIds(serverIds, localIds);

                if (merged.length === serverIds.length && merged.length === localIds.length) {
                    writeLocal(merged);
                    return merged;
                }

                return fetch(syncUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ broker_ids: merged.map(Number) }),
                }).then(function (res) {
                    if (!res.ok) {
                        throw new Error('Failed to sync saved brokers');
                    }
                    return res.json();
                }).then(function (payload) {
                    writeLocal((payload.broker_ids || merged).map(String));
                });
            })
            .catch(function () {
                // Non-blocking — local saves still work offline.
            });
    }

    window.bcSyncSavedBroker = function (brokerId, shouldSave) {
        if (!brokerId) {
            return Promise.resolve();
        }

        var url = '/profile/saved-brokers/' + brokerId;
        var opts = {
            method: shouldSave ? 'POST' : 'DELETE',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        };

        return fetch(url, opts)
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('Save sync failed');
                }
                return res.json();
            })
            .then(function (data) {
                if (data.broker_ids) {
                    writeLocal(data.broker_ids.map(String));
                }
            })
            .catch(function () {});
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', syncSavedBrokers);
    } else {
        syncSavedBrokers();
    }
})();
