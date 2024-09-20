<div align="center">

![Extension icon](Resources/Public/Icons/Extension.svg)

# TYPO3 extension `xima_typo3_internal_news`

[![Supported TYPO3 versions](https://badgen.net/badge/TYPO3/12%20&%2013/orange)](https://extensions.typo3.org/extension/xima_typo3_content_planner)

</div>

This extension provides an internal news system with custom access and notification capabilities for the TYPO3 backend.

![Toolbar](./Documentation/Images/toolbar-item.png)

## Features

* Create, edit and delete internal news records
* Define custom dates within news to notify backend users for specific events, e.g. next maintanance
* Assign news to specific backend user groups
* Top news occur as modal dialog on next backend login
* Dashboard widget for news overview
* Toolbar item for quick access to latest news

## Requirements

* TYPO3 >= 12.4 & PHP 8.1+

## Installation

### Composer

``` bash
composer require xima/xima-typo3-internal-news
```

### TER

ToDo

## Usage

Add the dashboard widget "Internal News" to your dashboard to get an overview of all news.

![Dashboard](./Documentation/Images/dashboard-widget.png)

A modal with the complete news content will be open by click on the news title.

![Modal](./Documentation/Images/news-modal.png)

The latest news will be also available in the toolbar.

![Toolbar](./Documentation/Images/toolbar-item.png)

### Notification

The notification feature can be used to inform backend users about specific events.

Therefore a custom date (as a single date or a recurrence rule) can be defined within the news record.

The notification hint will be displayed by default within a time slot 6 hours before the event (this can be adjusted in the extension settings).

![Notification](./Documentation/Images/notification.png)

## License

This project is licensed
under [GNU General Public License 2.0 (or later)](LICENSE.md).

News icon by Rock Zombie from <a href="https://thenounproject.com/icon/news-3141439/" target="_blank" title="Icon">
Noun Project</a> (CC BY 3.0)

Date icon by Yudhi Restu Pebriyanto from <a href="https://thenounproject.com/icon/date-7203889/" target="_blank" title="Icon">
Noun Project</a> (CC BY 3.0)
