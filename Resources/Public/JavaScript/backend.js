import Notification from "@typo3/backend/notification.js";
import Modal from '@typo3/backend/modal.js';
import ImmediateAction from "@typo3/backend/action-button/immediate-action.js";

class InternalNews {
  constructor() {
    document.addEventListener('widgetContentRendered', function (event) {
      if (event.target.querySelector('.widget-identifier-internalNews-news')) {
        init(true);
      }
    });

    const init = (withoutNotify = false) => {
      document.querySelectorAll('.internal-news').forEach(item => {
        const date = item.getAttribute('data-date');
        const dateTitle = item.getAttribute('data-date-title');
        const dateNotify = item.getAttribute('data-date-notify');
        const dateNotifyType = item.getAttribute('data-date-notify-type');
        const dateNote = item.getAttribute('data-date-note');
        const top = item.getAttribute('data-top');
        const inner = item.querySelector('.internal-news--inner').innerHTML;

        if (top) {
          setTimeout(() => {
            showInternalNews('Internal news', inner, date, dateTitle);
          }, 1000);
        }

        item.querySelectorAll('a, button').forEach(element => {
          element.addEventListener('click', e => {
            e.preventDefault();
            showInternalNews('Internal news', inner, date, dateTitle);
          });
        });

        if (dateNotify && !withoutNotify) {
          Notification[dateNotifyType](dateTitle, dateNote, 0, [
            {
              label: 'More',
              action: new ImmediateAction(function () {
                showInternalNews('Internal news', inner, date, dateTitle);
              })
            }
          ]);
        }
      });
    }

    const showInternalNews = (title, description, date, dateTitle) => {
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

    init();

  }
}

export default new InternalNews();
