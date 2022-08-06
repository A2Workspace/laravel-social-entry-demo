import axios from 'axios';

const moduleOptions = {
  authorization: '/auth/socialite',
  strategies: {
    client: {
      url: '/auth/socialite',
    },
    admin: {
      url: '/admin/auth/socialite',
    },
  },
};

export default {
  data() {
    return {
      socialEntryState: {
        processing: null,
        accessToken: null,
        lastAccessTokenResponse: null,
        strategy: 'client',
        options: {},
      },
    };
  },

  provide() {
    const $socialEntry = {
      setStrategy: this.setSocialEntryStrategy,
      authorize: this.authorize,
      completeAuthorization: this.completeAuthorization,
      loginWithToken: this.loginWithToken,
      connectWithToken: this.connectWithToken,
      disconnect: this.disconnect,
      getAccessToken: () => this.socialEntryState.accessToken,
      getLastAccessTokenResponse: () => this.socialEntryState.lastAccessTokenResponse,
    };

    return { $socialEntry };
  },

  methods: {
    setSocialEntryStrategy(name) {
      console.log(`[SocialEntry Module] setSocialEntryStrategy: ${name}`);

      if (!moduleOptions.strategies[name]) {
        throw new Error(`Strategy "${name}" is not defined!`);
      }

      this.socialEntryState.strategy = name;
      this.socialEntryState.options = moduleOptions.strategies[name];

      return this;
    },

    // =========================================================================
    // = Authorization
    // =========================================================================

    authorize(provider, continueUrl) {
      continueUrl = continueUrl || window.location.href;

      let baseUrl = window.location.origin || 'http://localhost:8000';
      let targetUrl = `${baseUrl}/auth/socialite?provider=${provider}&continue=${continueUrl}`;

      return {
        getTargetUrl() {
          return targetUrl;
        },
        redirect() {
          window.location.assign(targetUrl);
        },
      };
    },

    completeAuthorization(authCode = null, options = {}) {
      console.log('[SocialEntry Module] completeAuthorization');

      authCode = authCode || getParam('code');

      options = {
        ...this.socialEntryState.options,
        authCode,
        ...options,
      };

      if (!options.authCode) {
        console.log('[SocialEntry Module] completeAuthorization.aborted');
        return new Promise(() => {});
      }

      if (this.socialEntryState.processing) {
        return this.socialEntryState.processing;
      }

      if (this.socialEntryState.lastAccessTokenResponse) {
        return Promise.resolve(this.socialEntryState.lastAccessTokenResponse);
      }

      // Post to https://localhost:8000/auto/socialite/token
      // to grant access_token and get social user information.
      let request = axios.request({
        method: 'POST',
        url: `${options.url}/token`,
        data: {
          code: options.authCode,
        },
      });

      let resolvedRequest = request.then((response) => {
        this.socialEntryState.lastAccessTokenResponse = response;
        this.socialEntryState.accessToken = response.data.access_token;

        return response;
      });

      request = request.catch((error) => {
        if (!error.isAxiosError) {
          throw error;
        }

        resetParams();

        window.alert(`${error.response.data.message} (Status Code: ${error.response.status})`);
      });

      request = request.finally(() => {
        this.socialEntryState.processing = null;
      });

      this.socialEntryState.processing = request;

      return resolvedRequest;
    },

    // =========================================================================
    // = Social Sign In
    // =========================================================================

    loginWithToken(accessToken, options = {}) {
      accessToken = accessToken || this.socialEntryState.accessToken;

      options = {
        ...this.socialEntryState.options,
        accessToken,
        ...options,
      };

      // Post to https://localhost:8000/auto/socialite/login
      // to login by access token.
      let request = axios.request({
        method: 'POST',
        url: `${options.url}/login`,
        data: {
          access_token: options.accessToken,
        },
      });

      return request;
    },

    // =========================================================================
    // = Connections
    // =========================================================================

    connectWithToken(accessToken, options = {}) {
      accessToken = accessToken || this.socialEntryState.accessToken;

      options = {
        ...this.socialEntryState.options,
        accessToken,
        ...options,
      };

      // Post to https://localhost:8000/auto/socialite/connect
      // to connect social account to current logged in user.
      let request = axios.request({
        method: 'POST',
        url: `${options.url}/connect`,
        data: {
          access_token: options.accessToken,
        },
      });

      return request;
    },

    disconnect(provider, identifier, options = {}) {
      options = {
        ...this.socialEntryState.options,
        provider,
        identifier,
        ...options,
      };

      let request = axios.request({
        method: 'POST',
        url: `${options.url}/disconnect`,
        data: {
          type: options.provider,
          identifier: options.identifier,
        },
      });

      return request;
    },
  },

  async created() {
    this.setSocialEntryStrategy('client');

    console.log('[SocialEntry Module] created');
  },
};

// =============================================================================
// = Helpers
// =============================================================================

export function getParam(key) {
  return new URLSearchParams(window.location.search).get(key);
}

export function resetParams() {
  window.history.replaceState({}, window.document.title, window.location.href.split('?')[0]);
}
