/**
 * BrokersCourt Admin Dashboard Demo
 * Pure HTML/CSS/JS — no backend
 */
(function () {
  'use strict';

  const sidebar = document.getElementById('adminSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const toggleBtn = document.getElementById('sidebarToggle');
  const collapseBtn = document.getElementById('sidebarCollapse');
  const navItems = document.querySelectorAll('.nav-item[data-page]');
  const pageViews = document.querySelectorAll('.page-view');
  const pageTitle = document.getElementById('headerPageTitle');

  const pageTitles = {
    dashboard: 'Dashboard',
    brokers: 'Broker Management',
    'broker-details': 'Broker Details',
    'account-types': 'Account Options',
    promotions: 'Bonuses & Promotions',
    reviews: 'Reviews & Ratings',
    users: 'Users',
    blog: 'Blog & Articles',
    categories: 'Categories',
    tags: 'Tags',
    comparison: 'Compare (App)',
    'find-my-broker': 'Find My Broker (App)',
    'scam-brokers': 'Scam Brokers',
    'trading-tools': 'Trading Tools',
    advertisements: 'Advertisements',
    faqs: 'Broker FAQs',
    subscribers: 'Subscribers',
    pages: 'Static Pages',
    media: 'Videos & Photos',
    'live-channels': 'Live Channels',
    'online-polls': 'Online Polls',
    settings: 'Site Settings',
    admins: 'Admin Users',
    'activity-logs': 'Activity Logs',
    authors: 'Authors',
    languages: 'Languages',
    'social-items': 'Social Links'
  };

  function showPage(pageId) {
    pageViews.forEach(v => v.classList.remove('active'));
    navItems.forEach(n => n.classList.remove('active'));

    const view = document.getElementById('page-' + pageId);
    const nav = document.querySelector('.nav-item[data-page="' + pageId + '"]');

    if (view) view.classList.add('active');
    if (nav) nav.classList.add('active');
    if (pageTitle) pageTitle.textContent = pageTitles[pageId] || 'Dashboard';

    closeMobileSidebar();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  navItems.forEach(item => {
    item.addEventListener('click', () => showPage(item.dataset.page));
  });

  function closeMobileSidebar() {
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('mobile-open');
      overlay.classList.toggle('active');
    });
  }

  if (overlay) overlay.addEventListener('click', closeMobileSidebar);

  if (collapseBtn) {
    collapseBtn.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
  }

  // Tab switching within pages
  document.querySelectorAll('[data-tab-group]').forEach(group => {
    const groupName = group.dataset.tabGroup;
    const tabs = group.querySelectorAll('.bc-tab');
    const panels = document.querySelectorAll('[data-tab-panel="' + groupName + '"]');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const target = tab.dataset.tab;
        tabs.forEach(t => t.classList.remove('active'));
        panels.forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        const panel = document.querySelector('[data-tab-panel="' + groupName + '"][data-tab-id="' + target + '"]');
        if (panel) panel.classList.add('active');
      });
    });
  });

  // Toggle switches demo
  document.querySelectorAll('.toggle-switch').forEach(el => {
    el.addEventListener('click', () => el.classList.toggle('on'));
  });

  // Range slider value sync
  document.querySelectorAll('.ai-weight-row input[type="range"]').forEach(input => {
    const span = input.parentElement.querySelector('span');
    if (span) {
      input.addEventListener('input', () => { span.textContent = input.value; });
    }
  });

  // Demo toast on save buttons
  document.querySelectorAll('[data-demo-save]').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      showToast('Changes saved successfully (demo only)');
    });
  });

  // Demo modal triggers
  document.querySelectorAll('[data-demo-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.demoModal;
      if (target && window.jQuery) window.jQuery(target).modal('show');
    });
  });

  function showToast(message) {
    let toast = document.getElementById('demoToast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'demoToast';
      toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#151b24;border:1px solid #252f3f;color:#e2e8f0;padding:12px 20px;border-radius:8px;z-index:9999;box-shadow:0 4px 24px rgba(0,0,0,.4);border-left:3px solid #eab308;font-size:14px;transition:opacity .3s';
      document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.style.opacity = '1';
    setTimeout(() => { toast.style.opacity = '0'; }, 2800);
  }

  // Quick action navigation
  document.querySelectorAll('[data-goto]').forEach(el => {
    el.addEventListener('click', () => showPage(el.dataset.goto));
  });

  // Hash routing
  function routeFromHash() {
    const hash = (location.hash || '#dashboard').replace('#', '');
    if (pageTitles[hash]) showPage(hash);
  }

  window.addEventListener('hashchange', routeFromHash);
  navItems.forEach(item => {
    item.addEventListener('click', () => {
      location.hash = item.dataset.page;
    });
  });

  routeFromHash();
})();
