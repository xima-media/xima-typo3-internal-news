import InternalNewsUtils from '@xima/ximatypo3internalnews/utils.js';

class InternalNewsWidget {
  constructor() {
    document.addEventListener('widgetContentRendered', function (event) {
      if (event.target.querySelector('.widget-internal-news')) {
        document.querySelectorAll('.internal-news').forEach(item => {
          const newsId = item.getAttribute('data-news-id');
          item.querySelectorAll('a, button').forEach(element => {
            element.addEventListener('click', e => {
              e.preventDefault();
              InternalNewsUtils.fetchNews(newsId);
            });
          });
        });
      }
    });
  }
}

export default new InternalNewsWidget();
