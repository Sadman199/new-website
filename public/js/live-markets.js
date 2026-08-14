(function () {
    'use strict';

    var THEME = {
        colorTheme: 'light',
        isTransparent: true,
        locale: 'en',
        backgroundColor: '#ffffff',
    };

    var WIDGETS = {
        ticker: {
            src: 'https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js',
            config: Object.assign({}, THEME, {
                symbols: [
                    { proName: 'FX:EURUSD', title: 'EUR/USD' },
                    { proName: 'FX:GBPUSD', title: 'GBP/USD' },
                    { proName: 'FX:USDJPY', title: 'USD/JPY' },
                    { proName: 'FX:USDCHF', title: 'USD/CHF' },
                    { proName: 'FX:AUDUSD', title: 'AUD/USD' },
                    { proName: 'FX:USDCAD', title: 'USD/CAD' },
                    { proName: 'FX:NZDUSD', title: 'NZD/USD' },
                    { proName: 'FX:EURGBP', title: 'EUR/GBP' },
                ],
                showSymbolLogo: false,
                displayMode: 'adaptive',
            }),
        },
        rates: {
            src: 'https://s3.tradingview.com/external-embedding/embed-widget-forex-cross-rates.js',
            config: Object.assign({}, THEME, {
                width: '100%',
                height: 420,
                currencies: ['EUR', 'USD', 'JPY', 'GBP', 'CHF', 'AUD', 'CAD', 'NZD'],
            }),
        },
        heatmap: {
            src: 'https://s3.tradingview.com/external-embedding/embed-widget-forex-heat-map.js',
            config: Object.assign({}, THEME, {
                width: '100%',
                height: 460,
                currencies: ['EUR', 'USD', 'JPY', 'GBP', 'CHF', 'AUD', 'CAD', 'NZD', 'CNY'],
            }),
        },
        calendar: {
            src: 'https://s3.tradingview.com/external-embedding/embed-widget-events.js',
            config: Object.assign({}, THEME, {
                width: '100%',
                height: 480,
                importanceFilter: '-1,0,1',
                countryFilter: 'us,gb,eu,jp,au,ca,ch,nz',
            }),
        },
    };

    function mountWidget(container, key) {
        if (!container || container.dataset.loaded === 'true') {
            return;
        }

        var widget = WIDGETS[key];
        if (!widget) {
            return;
        }

        container.innerHTML = '';
        container.classList.add('bc-markets__widget-loading');
        container.textContent = 'Loading market data…';

        var wrap = document.createElement('div');
        wrap.className = 'tradingview-widget-container';
        wrap.style.width = '100%';
        wrap.style.height = '100%';

        var inner = document.createElement('div');
        inner.className = 'tradingview-widget-container__widget';
        wrap.appendChild(inner);

        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = widget.src;
        script.async = true;
        script.text = JSON.stringify(widget.config);

        script.onload = function () {
            container.classList.remove('bc-markets__widget-loading');
        };

        script.onerror = function () {
            container.classList.remove('bc-markets__widget-loading');
            container.textContent = 'Unable to load market widget. Please refresh the page.';
        };

        wrap.appendChild(script);
        container.innerHTML = '';
        container.appendChild(wrap);
        container.dataset.loaded = 'true';
    }

    var app = document.getElementById('bcMarketsApp');
    if (!app) {
        return;
    }

    var tickerHost = document.getElementById('bcMarketsTicker');
    if (tickerHost) {
        mountWidget(tickerHost, 'ticker');
    }

    function activateTab(tabKey) {
        app.querySelectorAll('.bc-markets__tab').forEach(function (tab) {
            var active = tab.getAttribute('data-markets-tab') === tabKey;
            tab.classList.toggle('is-active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        app.querySelectorAll('.bc-markets__panel').forEach(function (panel) {
            panel.classList.toggle('is-hidden', panel.getAttribute('data-markets-panel') !== tabKey);
        });

        var widgetHost = app.querySelector('.bc-markets__widget[data-widget="' + tabKey + '"]');
        mountWidget(widgetHost, tabKey);
    }

    app.querySelectorAll('.bc-markets__tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            activateTab(tab.getAttribute('data-markets-tab'));
        });
    });

    activateTab('rates');
})();
