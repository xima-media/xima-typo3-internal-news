import ImmediateAction from "@typo3/backend/action-button/immediate-action.js";
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import Client from '@typo3/backend/storage/client.js';
import Notification from "@typo3/backend/notification.js";
import BroadcastService from "@typo3/backend/broadcast-service.js";
import {BroadcastMessage} from "@typo3/backend/broadcast-message.js";
import InternalNewsUtils from '@xima/ximatypo3internalnews/utils.js';

const COMPONENT = 'internal_news';
const EVENT_SHOWN = 'notify-shown';
const EVENT_DISMISS = 'notify-dismiss';

class InternalNewsNotification {
  constructor() {
    // Ids another open backend tab is already showing - suppress duplicates here.
    this.shownByOthers = new Set();
    // Ids currently shown in this tab, mapped to their notification element, so
    // they can be closed when another tab dismisses them.
    this.shownHere = new Map();

    BroadcastService.listen();
    document.addEventListener(`typo3:${COMPONENT}:${EVENT_SHOWN}`, (event) => {
      this.shownByOthers.add(String(event.detail.id));
    });
    document.addEventListener(`typo3:${COMPONENT}:${EVENT_DISMISS}`, (event) => {
      this.dismiss(String(event.detail.id));
    });

    new AjaxRequest(TYPO3.settings.ajaxUrls.internal_news_notifies)
      .get()
      .then(async (response) => {
        const resolved = await response.resolve();
        if (resolved.notifies) {
          resolved.notifies.forEach(item => this.show(item, resolved.enableNotifySessionHide));
        }
      });
  }

  show(item, enableNotifySessionHide) {
    const id = String(item.id);

    // Skip when hidden for this session (persisted) or already shown in another tab.
    if (this.shownByOthers.has(id) || Client.get(`internal_news_notify--${id}`)) {
      return;
    }

    const actions = [
      {
        label: TYPO3.lang?.['internal_news.more'] ?? 'More',
        action: new ImmediateAction(() => {
          InternalNewsUtils.fetchNews(item.newsId);
        })
      }
    ];
    if (enableNotifySessionHide) {
      actions.push({
        label: TYPO3.lang?.['internal_news.hide'] ?? 'Hide',
        action: new ImmediateAction(() => {
          Client.set(`internal_news_notify--${id}`, true);
          this.post(EVENT_DISMISS, id);
        })
      });
    }

    Notification[item.notifyType](item.title, item.notifyMessage, 0, actions);

    // Tag the freshly rendered notification so other tabs can dismiss it.
    const element = document.querySelector('#alert-container .alert-list')?.lastElementChild;
    if (element) {
      this.shownHere.set(id, element);
    }

    // Inform other open tabs so they do not show the same notification again.
    this.post(EVENT_SHOWN, id);
  }

  dismiss(id) {
    const element = this.shownHere.get(id);
    if (element && typeof element.clear === 'function') {
      element.clear();
    }
    this.shownHere.delete(id);
  }

  post(eventName, id) {
    BroadcastService.post(new BroadcastMessage(COMPONENT, eventName, {id}));
  }
}

export default new InternalNewsNotification();
