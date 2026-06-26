-- Test fixtures for the xima_typo3_internal_news extension.
--
-- Imported automatically by ddev-typo3-multi-version-extension during
-- `ddev install` (which drops and recreates the database first, so plain
-- INSERTs are safe). Provides sample internal news, backend users/groups and
-- a pre-placed dashboard widget so the toolbar item and dashboard widget show
-- real data out of the box.
--
-- Internal news records live at pid=0 (the extension creates them there, see
-- CreateInternalNewsButtonProvider). An admin sees every record; a non-admin
-- sees records with an empty be_group (visible to everyone) or a be_group
-- matching one of their backend groups.
--
-- Backend logins created by this fixture (password: Password.1):
--   * editor    -> group "Internal News Editors" (sees public + editor news)
--   * marketing -> group "Marketing"             (sees public + marketing news)
-- The admin account from the TYPO3 setup sees every news item.

-- Backend groups -----------------------------------------------------------
-- groupMods grants the Dashboard module; availableWidgets grants non-admin
-- users permission to render the internal news widget (admins bypass this).
INSERT INTO be_groups (uid, pid, tstamp, crdate, deleted, hidden, title, groupMods, availableWidgets)
VALUES
    (90, 0, 1700000000, 1700000000, 0, 0, 'Internal News Editors', 'dashboard,web_list', 'internalNews-news'),
    (91, 0, 1700000000, 1700000000, 0, 0, 'Marketing', 'dashboard,web_list', 'internalNews-news');

-- Backend users (non-admin, password: Password.1) --------------------------
INSERT INTO be_users (uid, pid, tstamp, crdate, deleted, disable, admin, username, password, usergroup, realName, email)
VALUES
    (90, 0, 1700000000, 1700000000, 0, 0, 0, 'editor',
        '$2y$12$ftK/WBfGbPJA1IOae3Yn.exvXy.0HWKjwiYGBPwvg5s4mtbQTGOgm',
        '90', 'Editor Example', 'editor@example.org'),
    (91, 0, 1700000000, 1700000000, 0, 0, 0, 'marketing',
        '$2y$12$ftK/WBfGbPJA1IOae3Yn.exvXy.0HWKjwiYGBPwvg5s4mtbQTGOgm',
        '91', 'Marketing Example', 'marketing@example.org');

-- News records -------------------------------------------------------------
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
        0, 0, 0, '90'),
    (4, 0, 1700003000, 1700003000, 0, 0,
        'Spring campaign kickoff (Marketing only)',
        '<p>This news item is only visible to members of the "Marketing" backend group.</p>',
        0, 0, 0, '91'),
    (5, 0, 1700004000, 1700004000, 0, 0,
        'New backend dashboard (with image)',
        '<p>This news item ships an attached image to showcase media rendering in the news modal and the list preview. The screenshot below highlights the redesigned dashboard widget.</p>',
        0, 1, 0, '');

-- Example media file attached to news #5 ------------------------------------
-- The physical file is copied into fileadmin/ by the internal-news-media.sh
-- fixture script; this sys_file row references it in the default storage (1).
-- Hashes/size match Tests/Acceptance/Fixtures/files/internal-news-example.png.
INSERT INTO sys_file
    (uid, pid, tstamp, last_indexed, missing, storage, type, metadata, identifier, identifier_hash, folder_hash, extension, mime_type, name, sha1, size, creation_date, modification_date)
VALUES
    (1001, 0, 1700004000, 0, 0, 1, 2, 0,
        '/internal-news-example.png',
        '53ca324d3853757b5ad8e2228c3fc621ee36f3db',
        '42099b4af021e53fd8fd4e056c2568d7c2e3ffa8',
        'png', 'image/png', 'internal-news-example.png',
        '014b4ea511a99faadbb70529a17703d717ffb7bb',
        23506, 1700004000, 1700004000);

-- File reference linking sys_file #1001 to the media field of news #5 --------
INSERT INTO sys_file_reference
    (uid, pid, tstamp, crdate, deleted, hidden, sorting_foreign, uid_local, uid_foreign, tablenames, fieldname)
VALUES
    (1001, 0, 1700004000, 1700004000, 0, 0, 1, 1001, 5,
        'tx_ximatypo3internalnews_domain_model_news', 'media');

-- Date attached to news #2 (single date, with a notification enabled) -------
INSERT INTO tx_ximatypo3internalnews_domain_model_date
    (uid, pid, tstamp, crdate, deleted, hidden, title, type, single_date, recurrence, notify, notify_type, notify_message, news)
VALUES
    (1, 0, 1700001000, 1700001000, 0, 0,
        'Maintenance window', 'single_date', 1735693200, '', 1, 'info',
        'Reminder: scheduled maintenance is coming up.', 2);

-- Pre-placed dashboard with the internal news widget -----------------------
-- The widget hash is an arbitrary stable key; the value references the widget
-- identifier registered in Configuration/Services.yaml. The dashboard module
-- shows a user's be_dashboards rows on login (the JS grid auto-places widgets
-- that have no stored position).
--
-- editor (uid 90) and marketing (uid 91) get their dashboard directly. The
-- admin uid is not deterministic (the _cli_ user may take uid 1), so the
-- admin dashboard is created via a username lookup.
INSERT INTO be_dashboards (identifier, title, tstamp, crdate, cruser_id, widgets)
VALUES
    ('internal-news-fixture-editor',    'Internal News', 1700000000, 1700000000, 90,
        '{"4cac75c004c5efff82ff2f09981ae91841bf24b4":{"identifier":"internalNews-news"}}'),
    ('internal-news-fixture-marketing', 'Internal News', 1700000000, 1700000000, 91,
        '{"4cac75c004c5efff82ff2f09981ae91841bf24b4":{"identifier":"internalNews-news"}}');

INSERT INTO be_dashboards (identifier, title, tstamp, crdate, cruser_id, widgets)
SELECT 'internal-news-fixture-admin', 'Internal News', 1700000000, 1700000000, be_users.uid,
    '{"4cac75c004c5efff82ff2f09981ae91841bf24b4":{"identifier":"internalNews-news"}}'
FROM be_users
WHERE be_users.admin = 1 AND be_users.username = 'admin'
ORDER BY be_users.uid
LIMIT 1;
