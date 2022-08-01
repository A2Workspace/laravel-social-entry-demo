import axios from 'axios';

const moduleOptions = {
  endpoints: {
    authorization: '/auth/socialite',
    token: '/auth/socialite/token',
  },
};

export default {
  data() {
    return {
      processing: null,
      accessToken: null,
      lastAccessTokenResponse: null,
    };
  },

  provide() {
    const $socialEntry = {
      authorize: this.authorize,
      completeAuthorization: this.completeAuthorization,
    };

    return { $socialEntry };
  },

  methods: {
    /**
     * @param {string} provider
     * @param {(string|null)} continueUrl
     * @returns {RedirectRequest}
     */
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

    /**
     * @param {(string|null)} code
     * @returns {Promise}
     */
    completeAuthorization(code = null) {
      code = code || getParam('code');

      if (!code) {
        return new Promise(() => {});
      }

      if (this.processing) {
        return this.processing;
      }

      if (this.lastAccessTokenResponse) {
        return Promise.resolve(this.lastAccessTokenResponse);
      }

      // 這裡我們創建一個 AxiosRequest 並帶入 auth_code 向後端完成授權，
      // 預設的目標網址為 http://localhost:8000/auth/socialite/token ，
      // 並取回 social_user 資訊與 access_token 。
      let baseUrl = window.location.origin || 'http://localhost:8000';
      let endpoint = `${baseUrl}/auth/socialite/token`;

      let request = axios.request({
        method: 'post',
        url: endpoint,
        data: { code },
      });

      request = request.then((response) => {
        this.lastAccessTokenResponse = response;

        return response;
      });

      request = request.catch((error) => {
        if (!error.isAxiosError) {
          throw error;
        }

        // Reset params in current url.
        window.history.replaceState({}, window.document.title, window.location.href.split('?')[0]);

        window.alert(`${error.response.data.message} (Status Code: ${error.response.status})`);
      });

      request = request.finally(() => {
        this.processing = null;
      });

      this.processing = request;

      return request;
    },

    /**
     * @param {(AxiosResponse|null)} response
     * @returns {Promise}
     */
    loginWithToken(response) {
      response = response || this.lastAccessTokenResponse;

      const accessToken = response.data.access_token;

      let baseUrl = window.location.origin || 'http://localhost:8000';
      let endpoint = `${baseUrl}/auth/socialite/login`;

      let request = axios.request({
        method: 'post',
        url: endpoint,
        data: { access_token: accessToken },
      });
    },
  },
};

/**
 * @param {string} key
 * @returns {string}
 */
export function getParam(key) {
  return new URLSearchParams(window.location.search).get(key);
}

/**
 * @typedef RedirectRequest
 * @type {Object}
 * @property {function():string} getTargetUrl
 * @property {function():void} redirect
 */
