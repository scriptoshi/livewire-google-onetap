<div 
    x-data="{
        mounted: false,
        dark: document.documentElement.classList.contains('dark'),
        async init() {
            // Dynamically load the Google API script if it's not already loaded
            if (!window.google || !window.google.accounts) {
                await this.loadGoogleApi();
            }
            
            // Initialize Google One Tap
            this.initializeGoogleOneTap();
        },
        loadGoogleApi() {
            return new Promise((resolve, reject) => {
                if (window.google && window.google.accounts) {
                    return resolve();
                }
                
                const script = document.createElement('script');
                script.src = 'https://accounts.google.com/gsi/client';
                script.async = true;
                script.defer = true;
                script.onload = () => {
                    console.log('Google API loaded');
                    resolve();
                };
                script.onerror = () => {
                    console.error('Failed to load Google API');
                    reject();
                };
                document.head.appendChild(script);
            });
        },
        initializeGoogleOneTap() {
            if (!window.google || !window.google.accounts) {
                console.error('Google API not available');
                return;
            }
            
            window.google.accounts.id.initialize({
                client_id: '{{ $this->googleClientId }}',
                use_fedcm_for_prompt: true,
                callback: (response) => {
                    this.$wire.oneTapSignIn(response.credential);
                }
            });
            
            window.google.accounts.id.renderButton(
                this.$refs.googleOneTap,
                {
                    type: 'standard',
                    shape: 'rectangular',
                    theme: this.dark ? 'filled_black' : 'outline',
                    text: '{{ $type }}' === 'register' ? 'signup_with' : 'signin_with',
                    size: 'large',
                    width: '{{ $width }}',
                    height: '48px',
                    logo_alignment: 'left',
                }
            );
            
            this.mounted = true;
            window.google.accounts.id.prompt();
        }
    }"
    :class="{ 'border dark:border-gray-700 rounded p-1.5': mounted }"
    style="color-scheme: light"
    x-ref="googleOneTap"
>
</div>
