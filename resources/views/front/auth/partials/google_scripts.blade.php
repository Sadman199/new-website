@push('scripts')
    <script>
        (function () {
            var clientId = @json(\App\Support\GoogleOAuth::clientId());
            var credentialUrl = @json(route('user.google.credential'));
            var redirectUrl = @json(route('user.google.redirect'));
            var csrfToken = @json(csrf_token());

            window.handleGoogleCredential = function (response) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = credentialUrl;

                var csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = csrfToken;
                form.appendChild(csrf);

                var credential = document.createElement('input');
                credential.type = 'hidden';
                credential.name = 'credential';
                credential.value = response.credential;
                form.appendChild(credential);

                document.body.appendChild(form);
                form.submit();
            };

            function bindFallbackButton() {
                var button = document.getElementById('googleSignInBtn');
                if (!button || button.dataset.fallbackBound === '1') {
                    return;
                }

                button.dataset.fallbackBound = '1';
                button.addEventListener('click', function () {
                    if (clientId) {
                        window.location.href = redirectUrl;
                        return;
                    }

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Google sign-in',
                            text: 'Google sign-in is not configured yet. Add your Google Client ID in Admin → Settings → Google Sign-In, or in the .env file.',
                            confirmButtonColor: '#007AAD',
                        });
                    } else {
                        alert('Google sign-in is not configured yet. Please use email and password.');
                    }
                });
            }

            function initGoogleButton() {
                var button = document.getElementById('googleSignInBtn');
                var container = document.getElementById('googleSignInContainer');

                if (!clientId || !button || !container || !window.google || !google.accounts || !google.accounts.id) {
                    bindFallbackButton();
                    return;
                }

                google.accounts.id.initialize({
                    client_id: clientId,
                    callback: window.handleGoogleCredential,
                    auto_select: false,
                });

                var width = Math.min(button.offsetWidth || container.offsetWidth || 320, 400);
                var textMode = button.getAttribute('data-google-text') || 'signin_with';

                google.accounts.id.renderButton(container, {
                    type: 'standard',
                    theme: 'outline',
                    size: 'large',
                    text: textMode,
                    shape: 'rectangular',
                    logo_alignment: 'left',
                    width: width,
                });

                button.classList.add('ua-google-wrap--hidden');
                button.setAttribute('aria-hidden', 'true');
                container.classList.remove('ua-google-wrap--hidden');
                container.removeAttribute('aria-hidden');
            }

            function boot() {
                if (clientId) {
                    var script = document.createElement('script');
                    script.src = 'https://accounts.google.com/gsi/client';
                    script.async = true;
                    script.defer = true;
                    script.onload = function () {
                        var attempts = 0;
                        var timer = setInterval(function () {
                            attempts += 1;
                            if (window.google && google.accounts && google.accounts.id) {
                                clearInterval(timer);
                                initGoogleButton();
                            } else if (attempts > 50) {
                                clearInterval(timer);
                                bindFallbackButton();
                            }
                        }, 100);
                    };
                    script.onerror = bindFallbackButton;
                    document.head.appendChild(script);
                    return;
                }

                bindFallbackButton();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot);
            } else {
                boot();
            }
        })();
    </script>
@endpush
