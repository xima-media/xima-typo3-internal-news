import InternalNewsUtils from '@xima/ximatypo3internalnews/utils.js';

class InternalNewsToolbar {
  constructor() {
      document.querySelectorAll('.internal-news').forEach(item => {
        const newsId = item.getAttribute('data-news-id');
        const top = item.getAttribute('data-top');

        if (top) {
          setTimeout(() => {
            InternalNewsUtils.fetchNews(newsId);
          }, 1000);
        }

        item.querySelectorAll('a, button').forEach(element => {
          element.addEventListener('click', e => {
            e.preventDefault();
            InternalNewsUtils.fetchNews(newsId);
          });
        });
      });
    }
}

export default new InternalNewsToolbar();
