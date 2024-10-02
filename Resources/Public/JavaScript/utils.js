import Modal from '@typo3/backend/modal.js';
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";

class InternalNewsUtils {
  fetchNews = (id) => {
    new AjaxRequest(TYPO3.settings.ajaxUrls.internal_news_detail)
      .withQueryArguments({newsId: id})
      .get()
      .then(async (response) => {
        const resolved = await response.resolve();
        this.showInternalNews('Internal news', resolved.result);
      });
  }

  showInternalNews = (title, description) => {
    Modal.advanced({
      title: title,
      content: document.createRange()
        .createContextualFragment(description),
      size: Modal.sizes.large,
      staticBackdrop: true,
      buttons: [
        {
          text: 'Close',
          name: 'close',
          icon: 'actions-close',
          active: true,
          btnClass: 'btn-secondary',
          trigger: function (event, modal) {
            modal.hideModal();
          }
        }
      ]
    });
  }
}

export default new InternalNewsUtils();
