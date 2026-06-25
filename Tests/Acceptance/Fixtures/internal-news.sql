-- Test fixtures for the xima_typo3_internal_news extension.
--
-- Imported automatically by ddev-typo3-multi-version-extension during
-- `ddev install`. Provides sample internal news so the toolbar item and
-- dashboard widget show real data out of the box.
--
-- Internal news records live at pid=0 (the extension creates them there,
-- see CreateInternalNewsButtonProvider). An admin sees every record; a
-- non-admin sees records with an empty be_group (visible to everyone) or a
-- be_group matching one of their backend groups.

-- Backend group used to demonstrate group-targeted news.
INSERT INTO be_groups (uid, pid, tstamp, crdate, deleted, hidden, title)
VALUES (90, 0, 1700000000, 1700000000, 0, 0, 'Internal News Editors')
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- News records.
INSERT INTO tx_ximatypo3internalnews_domain_model_news
    (uid, pid, tstamp, crdate, deleted, hidden, title, description, top, media, dates, be_group)
VALUES
    (1, 0, 1700000000, 1700000000, 0, 0,
        'Welcome to Internal News',
        '<p>This is a pinned welcome message visible to every backend user. Use the toolbar item or the dashboard widget to browse internal news.</p>',
        1, 0, 0, ''),
    (2, 0, 1700001000, 1700001000, 0, 0,
        'Scheduled maintenance this weekend',
        '<p>The system will be unavailable during the maintenance window. See the attached date for details.</p>',
        0, 0, 1, ''),
    (3, 0, 1700002000, 1700002000, 0, 0,
        'New editorial workflow (Editors only)',
        '<p>This news item is only visible to members of the "Internal News Editors" backend group.</p>',
        0, 0, 0, '90')
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), be_group = VALUES(be_group), top = VALUES(top), dates = VALUES(dates);

-- Date attached to news #2 (single date, with a notification enabled).
INSERT INTO tx_ximatypo3internalnews_domain_model_date
    (uid, pid, tstamp, crdate, deleted, hidden, title, type, single_date, recurrence, notify, notify_type, notify_message, news)
VALUES
    (1, 0, 1700001000, 1700001000, 0, 0,
        'Maintenance window', 'single_date', 1735693200, '', 1, 'info',
        'Reminder: scheduled maintenance is coming up.', 2)
ON DUPLICATE KEY UPDATE title = VALUES(title), single_date = VALUES(single_date), notify = VALUES(notify), news = VALUES(news);
