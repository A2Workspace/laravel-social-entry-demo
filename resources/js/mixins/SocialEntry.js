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
      accessToken: () => this.socialEntryState.accessToken,
      lastAccessTokenResponse: () => this.socialEntryState.lastAccessTokenResponse,
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

    completeAuthorization(code = null) {
      code = code || getParam('code');

      if (!code) {
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
        data: { code },
      });

      request = request.then((response) => {
        this.socialEntryState.lastAccessTokenResponse = response;

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

      return request;
    },

    /**
     * @param {(AxiosResponse|null)} response
     * @returns {Promise}
     */
    loginWithToken(response) {
      response = response || this.socialEntryState.lastAccessTokenResponse;

      const accessToken = response.data.access_token;

      let baseUrl = window.location.origin || 'http://localhost:8000';
      let endpoint = `${baseUrl}/auth/socialite/login`;

      let request = axios.request({
        method: 'POST',
        url: endpoint,
        data: { access_token: accessToken },
      });
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
