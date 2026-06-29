import Modal from '@typo3/backend/modal.js';
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";

class InternalNewsUtils {
  fetchNews = (id) => {
    new AjaxRequest(TYPO3.settings.ajaxUrls.internal_news_detail)
      .withQueryArguments({newsId: id})
      .get()
      .then(async (response) => {
        const resolved = await response.resolve();
        this.showInternalNews(this.title(resolved.labels), resolved.result, resolved.labels);
      });
  }

  fetchNewsList = () => {
    new AjaxRequest(TYPO3.settings.ajaxUrls.internal_news_list)
      .get()
      .then(async (response) => {
        const resolved = await response.resolve();
        this.showInternalNewsList(this.title(resolved.labels), resolved.result, resolved.labels);
      });
  }

  title = (labels) => labels?.title ?? TYPO3.lang?.['internal_news.title'] ?? 'Internal News';

  closeButton = (labels) => ({
    text: labels?.close ?? TYPO3.lang?.['internal_news.close'] ?? 'Close',
    name: 'close',
    icon: 'actions-close',
    active: true,
    btnClass: 'btn-secondary',
    trigger: (event, modal) => {
      modal.hideModal();
    }
  })

  showInternalNews = (title, description, labels) => {
    Modal.advanced({
      title: title,
      content: document.createRange()
        .createContextualFragment(description),
      size: {width: Modal.sizes.medium, height: Modal.sizes.default},
      staticBackdrop: true,
      buttons: [this.closeButton(labels)]
    });
  }

  showInternalNewsList = (title, content, labels) => {
    Modal.advanced({
      title: title,
      content: document.createRange()
        .createContextualFragment(content),
      size: {width: Modal.sizes.medium, height: Modal.sizes.default},
      staticBackdrop: true,
      callback: (modal) => {
        modal.querySelectorAll('.internal-news').forEach(item => {
          const newsId = item.getAttribute('data-news-id');
          item.querySelectorAll('a, button').forEach(element => {
            element.addEventListener('click', e => {
              e.preventDefault();
              this.fetchNews(newsId);
            });
          });
        });
      },
      buttons: [this.closeButton(labels)]
    });
  }
}

export default new InternalNewsUtils();
