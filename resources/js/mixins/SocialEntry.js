import axios from 'axios';

const moduleOptions = {
  authorization: '/auth/socialite',
  strategies: {
    user: {
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
      },
    };
  },

  provide() {
    const $socialEntry = {
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

    completeAuthorization(authCode = null) {
      authCode = authCode || getParam('code');

      if (!authCode) {
        return new Promise(() => {});
      }

      if (this.socialEntryState.processing) {
        return this.socialEntryState.processing;
      }

      if (this.socialEntryState.lastAccessTokenResponse) {
        return Promise.resolve(this.socialEntryState.lastAccessTokenResponse);
      }

      // 這裡我們創建一個 AxiosRequest 並帶入 auth_code 向後端完成授權，
      // 預設的目標網址為 http://localhost:8000/auth/socialite/token ，
      // 並取回 social_user 資訊與 access_token 。
      let baseUrl = window.location.origin || 'http://localhost:8000';
      let endpoint = `${baseUrl}/auth/socialite/token`;

      let request = axios.request({
        method: 'POST',
        url: endpoint,
        data: {
          code: authCode,
        },
      });

      let responsed = request.then((response) => {
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

      return responsed;
    },

    // =========================================================================
    // = Social Sign In
    // =========================================================================

    loginWithToken(accessToken) {
      accessToken = accessToken || this.socialEntryState.accessToken;

      let baseUrl = window.location.origin || 'http://localhost:8000';
      let endpoint = `${baseUrl}/auth/socialite/login`;

      let request = axios.request({
        method: 'POST',
        url: endpoint,
        data: {
          access_token: accessToken,
        },
      });

      return request;
    },

    // =========================================================================
    // = Connections
    // =========================================================================

    connectWithToken(accessToken) {
      accessToken = accessToken || this.socialEntryState.accessToken;

      let baseUrl = window.location.origin || 'http://localhost:8000';
      let endpoint = `${baseUrl}/auth/socialite/connect`;

      let request = axios.request({
        method: 'POST',
        url: endpoint,
        data: {
          access_token: accessToken,
        },
      });

      return request;
    },

    disconnect(provider, identifier) {
      let baseUrl = window.location.origin || 'http://localhost:8000';
      let endpoint = `${baseUrl}/auth/socialite/disconnect`;

      let request = axios.request({
        method: 'POST',
        url: endpoint,
        data: {
          type: provider,
          identifier,
        },
      });

      return request;
    },
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
