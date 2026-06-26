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

      document.querySelectorAll('.internal-news--show-all').forEach(element => {
        element.addEventListener('click', e => {
          e.preventDefault();
          InternalNewsUtils.fetchNewsList();
        });
      });
    }
}

export default new InternalNewsToolbar();
