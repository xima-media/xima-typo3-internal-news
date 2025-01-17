import ImmediateAction from "@typo3/backend/action-button/immediate-action.js";
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import Client from '@typo3/backend/storage/client.js';
import Notification from "@typo3/backend/notification.js";
import InternalNewsUtils from '@xima/ximatypo3internalnews/utils.js';


class InternalNewsNotification {
  constructor() {
    new AjaxRequest(TYPO3.settings.ajaxUrls.internal_news_notifies)
      .get()
      .then(async (response) => {
        const resolved = await response.resolve();
        if (resolved.notifies) {
          resolved.notifies.forEach(item => {
            let actions = [
              {
                label: TYPO3.lang !== undefined ? TYPO3.lang['internal_news.more'] : 'More',
                action: new ImmediateAction(() => {
                  InternalNewsUtils.fetchNews(item.newsId);
                })
              }
            ];
            if (resolved.enableNotifySessionHide) {
              actions.push({
                label: TYPO3.lang !== undefined ? TYPO3.lang['internal_news.hide'] : 'Hide',
                action: new ImmediateAction(() => {
                  Client.set(`internal_news_notify--${item.id}`, true);
                })
              });
            }

            if (!resolved.enableNotifySessionHide || Client.get(`internal_news_notify--${item.id}`)) {
              return;
            }
            Notification[item.notifyType](item.title, item.notifyMessage, 0, actions);
          });
        }
      });
  }
}

export default new InternalNewsNotification();
