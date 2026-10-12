# Graph Report - omnio-image-optimizer  (2026-10-12)

## Corpus Check
- Large corpus: 177 files · ~655,670 words. Semantic extraction will be expensive (many Claude tokens). Consider running on a subfolder, or use --no-semantic to run AST-only.

## Summary
- 1544 nodes · 2781 edges · 109 communities (90 shown, 19 thin omitted)
- Extraction: 80% EXTRACTED · 20% INFERRED · 0% AMBIGUOUS · INFERRED: 546 edges (avg confidence: 0.82)
- Token cost: 875,599 input · 0 output

## Community Hubs (Navigation)
- [[_COMMUNITY_Playwright Config|Playwright Config]]
- [[_COMMUNITY_Asset Enqueueing|Asset Enqueueing]]
- [[_COMMUNITY_BackupManager Core|BackupManager Core]]
- [[_COMMUNITY_npm Package Manifest|npm Package Manifest]]
- [[_COMMUNITY_Composer Manifest|Composer Manifest]]
- [[_COMMUNITY_Activation & Bootstrap|Activation & Bootstrap]]
- [[_COMMUNITY_URL Lookup & Migrations|URL Lookup & Migrations]]
- [[_COMMUNITY_App Facade Methods|App Facade Methods]]
- [[_COMMUNITY_Conversion & URL Map|Conversion & URL Map]]
- [[_COMMUNITY_Model & Locking|Model & Locking]]
- [[_COMMUNITY_Stats & Status Counts|Stats & Status Counts]]
- [[_COMMUNITY_REST Controllers & Policies|REST Controllers & Policies]]
- [[_COMMUNITY_Request Validation & Coordinator|Request Validation & Coordinator]]
- [[_COMMUNITY_Stats Resource API|Stats Resource API]]
- [[_COMMUNITY_Logging & Backup Controller|Logging & Backup Controller]]
- [[_COMMUNITY_CLI Commands & Scanner|CLI Commands & Scanner]]
- [[_COMMUNITY_Bulk Runner|Bulk Runner]]
- [[_COMMUNITY_Scan Scheduling & Cancel|Scan Scheduling & Cancel]]
- [[_COMMUNITY_MIME Repair|MIME Repair]]
- [[_COMMUNITY_Admin Assets & Notices|Admin Assets & Notices]]
- [[_COMMUNITY_REST Field|REST Field]]
- [[_COMMUNITY_Media Library Handler|Media Library Handler]]
- [[_COMMUNITY_Editor Assets|Editor Assets]]
- [[_COMMUNITY_Admin Menu|Admin Menu]]
- [[_COMMUNITY_Optimizer Cleanup|Optimizer Cleanup]]
- [[_COMMUNITY_Database Rewriter|Database Rewriter]]
- [[_COMMUNITY_404 Fallback|404 Fallback]]
- [[_COMMUNITY_StoreSettings|StoreSettings]]
- [[_COMMUNITY_Scan Runner State|Scan Runner State]]
- [[_COMMUNITY_Environment Diagnostics|Environment Diagnostics]]
- [[_COMMUNITY_Conversion Engines|Conversion Engines]]
- [[_COMMUNITY_Engine Factory Chain|Engine Factory Chain]]
- [[_COMMUNITY_Upload Interceptor|Upload Interceptor]]
- [[_COMMUNITY_Container & REST Namespace|Container & REST Namespace]]
- [[_COMMUNITY_Config Store|Config Store]]
- [[_COMMUNITY_Router Route Object|Router Route Object]]
- [[_COMMUNITY_Admin Icons|Admin Icons]]
- [[_COMMUNITY_Admin App Mount|Admin App Mount]]
- [[_COMMUNITY_Masthead & Notices|Masthead & Notices]]
- [[_COMMUNITY_Admin UI Components|Admin UI Components]]
- [[_COMMUNITY_Backups Page|Backups Page]]
- [[_COMMUNITY_Settings State & Toasts|Settings State & Toasts]]
- [[_COMMUNITY_Library Progress Widgets|Library Progress Widgets]]
- [[_COMMUNITY_Card & Section|Card & Section]]
- [[_COMMUNITY_Bulk Page Client Pumping|Bulk Page Client Pumping]]
- [[_COMMUNITY_Block Editor Panel|Block Editor Panel]]
- [[_COMMUNITY_Media Modal Panel|Media Modal Panel]]
- [[_COMMUNITY_Optimize Progress Bar|Optimize Progress Bar]]
- [[_COMMUNITY_CLI Smoke Script|CLI Smoke Script]]
- [[_COMMUNITY_CLI Bulk Harness|CLI Bulk Harness]]
- [[_COMMUNITY_PHP Test Harness|PHP Test Harness]]
- [[_COMMUNITY_Smoke Spec|Smoke Spec]]
- [[_COMMUNITY_Library Scan e2e|Library Scan e2e]]
- [[_COMMUNITY_Bulk Runner Internals|Bulk Runner Internals]]
- [[_COMMUNITY_Bulk Job Cron|Bulk Job Cron]]
- [[_COMMUNITY_Settings-Driven Runs|Settings-Driven Runs]]
- [[_COMMUNITY_Scan Completion|Scan Completion]]
- [[_COMMUNITY_App Facade Accessors|App Facade Accessors]]
- [[_COMMUNITY_Boot Config & Bindings|Boot Config & Bindings]]
- [[_COMMUNITY_Playwright e2e Config|Playwright e2e Config]]
- [[_COMMUNITY_Settings Registration|Settings Registration]]
- [[_COMMUNITY_Deactivation Unschedule|Deactivation Unschedule]]
- [[_COMMUNITY_Foreign Notice Stripping|Foreign Notice Stripping]]
- [[_COMMUNITY_OptimizeRestore Flow|Optimize/Restore Flow]]
- [[_COMMUNITY_Dist Build Docs|Dist Build Docs]]
- [[_COMMUNITY_Global Helpers|Global Helpers]]
- [[_COMMUNITY_Hook Prefix|Hook Prefix]]
- [[_COMMUNITY_Bulk Cancel|Bulk Cancel]]
- [[_COMMUNITY_Architecture Conventions|Architecture Conventions]]
- [[_COMMUNITY_Architecture Rules|Architecture Rules]]
- [[_COMMUNITY_Environment Gotchas|Environment Gotchas]]
- [[_COMMUNITY_Destructive Path Safety|Destructive Path Safety]]
- [[_COMMUNITY_Product Scope & Features|Product Scope & Features]]
- [[_COMMUNITY_Sideload & MIME Handling|Sideload & MIME Handling]]
- [[_COMMUNITY_Backup Deletion Incident|Backup Deletion Incident]]
- [[_COMMUNITY_Engine Selection Docs|Engine Selection Docs]]
- [[_COMMUNITY_Notice Test & Copy|Notice Test & Copy]]
- [[_COMMUNITY_Bulk Run Orchestration|Bulk Run Orchestration]]
- [[_COMMUNITY_Backup Manifest Integrity|Backup Manifest Integrity]]
- [[_COMMUNITY_Upload Optimization Guards|Upload Optimization Guards]]
- [[_COMMUNITY_Engine Preference Docs|Engine Preference Docs]]
- [[_COMMUNITY_Backup Retention|Backup Retention]]
- [[_COMMUNITY_Rewriter Safety Rules|Rewriter Safety Rules]]
- [[_COMMUNITY_Bulk Batch Processing|Bulk Batch Processing]]
- [[_COMMUNITY_Media Modal Buttons|Media Modal Buttons]]
- [[_COMMUNITY_PHPCS Compliance|PHPCS Compliance]]
- [[_COMMUNITY_Admin CSS & Foreign Notices|Admin CSS & Foreign Notices]]
- [[_COMMUNITY_Media Grid Bulk Screenshot|Media Grid Bulk Screenshot]]
- [[_COMMUNITY_Bulk Tab Screenshot|Bulk Tab Screenshot]]
- [[_COMMUNITY_Maintenance Screenshot|Maintenance Screenshot]]
- [[_COMMUNITY_Attachment Details Screenshots|Attachment Details Screenshots]]

## God Nodes (most connected - your core abstractions)
1. `Logger` - 105 edges
2. `__()` - 71 edges
3. `OptimizationLog` - 68 edges
4. `Scanner` - 53 edges
5. `BackupManager` - 49 edges
6. `Fixed Issues` - 45 edges
7. `StoreSettings` - 44 edges
8. `ScanRunner` - 42 edges
9. `AttachmentConverter` - 41 edges
10. `Build History (Units)` - 39 edges

## Surprising Connections (you probably didn't know these)
- `Config` --semantically_similar_to--> `config/optimizer.php (optimizer defaults and bounds)`  [AMBIGUOUS] [semantically similar]
  framework/Config.php → config/optimizer.php
- `tests/php/run.sh rewriter-test` --references--> `omnio-image-optimizer npm package`  [INFERRED]
  agent.md → package.json
- `chinmoybiswas93/omnio-image-optimizer Composer package` --references--> `DBMigrator`  [INFERRED]
  composer.json → context/architecture.md
- `OptimizationLog` --calls--> `cli_status_count()`  [INFERRED]
  app/Models/OptimizationLog.php → tests/php/cli-bulk-e2e.php
- `OptimizationLog` --calls--> `harness_count_log_rows()`  [INFERRED]
  app/Models/OptimizationLog.php → tests/php/wp.php

## Hyperedges (group relationships)
- **Cron scheduler lifecycle (activate schedules, register self-heals, deactivate unschedules)** — handlers_activationhandler_activatesite, handlers_deactivationhandler_handle, scheduler_jobrunner_jobrunner, scheduler_bulkjobrunner_bulkjobrunner, scheduler_scanjobrunner_scanjobrunner, uninstall_routine [EXTRACTED 1.00]
- **Attachment optimization status exposed to media column, media modal and block editor REST field** — handlers_medialibraryhandler_optimization_payload, handlers_medialibraryhandler_column_state, handlers_medialibraryhandler_expose_to_js, handlers_restfieldhandler_get_field, models_optimizationlog_optimizationlog, services_attachmentconverter_attachmentconverter [EXTRACTED 1.00]
- **Backup repair and purge flow (reconcile before purge, orphan sweep)** — controllers_backupcontroller_reconcile, controllers_backupcontroller_purge, cli_commands_repair_backups, scheduler_jobrunner_purge_manifests, backup_backupmanager_backupmanager [INFERRED 0.85]
- **Attachment conversion pipeline (lock, backup, encode, rewrite, log)** — services_attachmentconverter_convert, services_lock_lock, backup_backupmanager_backup, services_optimizer_optimize, rewrite_databaserewriter_databaserewriter, models_optimizationlog_optimizationlog, models_urllookup_urllookup [EXTRACTED 1.00]
- **REST route = Router + Policy + Request args + Controller** — routes_api, policies_policy_policy, requests_optimizerequest_optimizerequest, controllers_controller_controller, framework_router_router [INFERRED 0.85]
- **Backup repair-then-purge flow** — controllers_optimizecontroller_reconcilemime, backup_backupmanager_reconcile, backup_backupmanager_purge_orphans, models_optimizationlog_unbackedpage, repair_before_destroy [INFERRED 0.85]
- **Conversion engine strategy chain (Imagick > cwebp > GD)** — engine_engineinterface, engine_abstractengine_abstractengine, engine_imagickengine_imagickengine, engine_cwebpengine_cwebpengine, engine_gdengine_gdengine, engine_enginefactory_enginefactory [EXTRACTED 1.00]
- **Bulk full run: scan, optimize, scan** — bulk_coordinator_coordinator, bulk_scanrunner_scanrunner, bulk_runner_runner, scheduler_bulkjobrunner_bulkjobrunner, scheduler_scanjobrunner_scanjobrunner [EXTRACTED 1.00]
- **URL rewrite after conversion (map, DB rewrite, 404 fallback)** — rewrite_urlmap_urlmap, rewrite_databaserewriter_databaserewriter, rewrite_fallback404_fallback404, models_urllookup_urllookup, bulk_runner_runner [INFERRED 0.85]
- **Application boot: config, container bindings, facade, hook manifests, REST routes** — framework_application_construct, framework_config_fromdirectory, framework_container_singleton, app_app_app, boot_bindings, hooks_actions, hooks_filters, framework_router_flush [EXTRACTED 1.00]
- **Versioned schema migration and URL backfill for 404 fallback** — database_dbmigrator_migrateup, migrations_logmigrator_migrate, migrations_urlmigrator_migrate, database_databackfills_backfillurls, models_urllookup_urllookup, concept_404_fallback [EXTRACTED 1.00]
- **REST dispatch: Router builds callbacks resolving controllers and policies via Container** — framework_router_router, framework_route_route, framework_router_makecallback, framework_router_makepermissioncallback, framework_container_make, policies_verifyrequest [EXTRACTED 1.00]
- **Bulk run: leading scan, optimize batches, closing scan** — pages_bulkpage_startoptimize, rest_bulk_run, pages_bulkpage_pumpscan, pages_bulkpage_pumpbulk, rest_library_scan_batch, rest_bulk_batch, rest_bulk_phase, partials_runprogress_runprogress [EXTRACTED 1.00]
- **Single scan snapshot feeding every library figure** — concept_scan_snapshot, partials_herostats_herostats, partials_scansummary_scansummary, partials_librarycard_librarycard, pages_bulkpage_absorb [EXTRACTED 1.00]
- **Backup repair-before-purge safety flow** — pages_backupspage_repair, pages_backupspage_purge, rest_backups_reconcile, rest_backups_purge, pages_backupspage_confirm_word, components_confirmdialog_confirmdialog [EXTRACTED 1.00]
- **Per-attachment optimization panel parity (media modal vs block editor)** — media_media_buildpanel, editor_panel_panel, media_media_status_meta, editor_panel_status_meta, routes_api [EXTRACTED 1.00]
- **Local socket/site pairing guard for harnesses** — php_run, php_run_cli, php_bootstrap_harness_require_site, php_web_runner [INFERRED 0.85]
- **Backup data-loss protection in harnesses** — php_convert_restore_e2e, php_wp_harness_cleanup, backup_backupmanager_backupmanager, controllers_backupcontroller_backupcontroller [INFERRED 0.85]
- **Attachment conversion pipeline** — context_architecture_attachmentconverter, context_architecture_backupmanager, context_architecture_optimizer, context_architecture_urlmap, context_architecture_databaserewriter, context_architecture_optimizationlog [EXTRACTED 1.00]
- **Engine fallback chain (Imagick → cwebp → GD)** — context_architecture_enginefactory, context_architecture_imagickengine, context_architecture_cwebpengine, context_architecture_gd_engine, context_architecture_optimizer [EXTRACTED 1.00]
- **Backup, repair-before-sweep and restore safety net** — context_architecture_backupmanager_reconcile, context_architecture_purge_orphans, context_architecture_manifest_is_intact, context_ai_workflow_rules_glob_backup_incident, context_issues_upload_backup_collision [INFERRED 0.85]
- **Attachment convert pipeline** — done_05_attachment_conversion_attachmentconverter, done_03_backup_retention_backupmanager, done_02_upload_optimization_optimizer, done_04_url_rewriting_urlmap, done_04_url_rewriting_databaserewriter [EXTRACTED 1.00]
- **Background scan -> optimize -> scan chain** — done_12_centralized_scan_stats_coordinator, done_12_centralized_scan_stats_scanrunner, done_12_centralized_scan_stats_scanjobrunner, done_12_centralized_scan_stats_bulkjobrunner, done_06_bulk_rest_cli_bulk_runner [EXTRACTED 1.00]
- **Backup repair-before-sweep lifecycle** — memory_fixed_issues_backupmanager_reconcile, done_13_backup_purge_confirm_modal_backupmanager_purge_orphans, done_13_backup_purge_confirm_modal_jobrunner_purge_manifests, memory_fixed_issues_mimereconciler, done_13_backup_purge_confirm_modal_invariant_26_repair_before_sweep [INFERRED 0.85]

## Communities (109 total, 19 thin omitted)

### Community 39 - "Asset Enqueueing"
Cohesion: 0.13
Nodes (15): defaultConfig, path, Webpack build config (admin/media/editor entries), admin bundle entry (resources/admin/bootstrap/app.jsx), media bundle entry (resources/media/media.js), editor bundle entry (resources/editor/index.jsx), AssetHandler::register(), AssetHandler::enqueue() (+7 more)

### Community 22 - "BackupManager Core"
Cohesion: 0.16
Nodes (6): omnio_io_remove_dir(), BackupManager, wp_basename(), trailingslashit(), EnvironmentReport::filesystem(), Logger::ensure_dir()

### Community 34 - "npm Package Manifest"
Cohesion: 0.11
Nodes (18): name, version, description, license, private, scripts, build, start (+10 more)

### Community 33 - "Composer Manifest"
Cohesion: 0.11
Nodes (18): name, description, type, license, authors, require, php, autoload (+10 more)

### Community 5 - "Activation & Bootstrap"
Cohesion: 0.09
Nodes (8): DBMigrator, DataBackfills, DeactivationHandler, ActivationHandler, Application, boot/app.php bootstrap, Omnio Image Optimizer main plugin file, OMNIO_IO_* constants (VERSION/FILE/DIR/URL/BASENAME)

### Community 27 - "URL Lookup & Migrations"
Cohesion: 0.12
Nodes (13): LogMigrator, UrlMigrator, UrlLookup, SCHEMA_VERSION = 3 (omnio_io_schema_version), DBMigrator::migrateUp(), DBMigrator::maybeMigrateDBChanges(), DBMigrator::dropTables(), DBMigrator::runBackfills() (+5 more)

### Community 11 - "App Facade Methods"
Cohesion: 0.07
Nodes (14): App, omnio_io_app(), omnio_io_config(), omnio_io_view(), routes.php (route manifest), Application::addRestApiInitAction(), Container::make(), Container::build() (reflection auto-wire, circular detection) (+6 more)

### Community 10 - "Conversion & URL Map"
Cohesion: 0.09
Nodes (8): AttachmentConverter, UrlMap, untrailingslashit(), wp_make_link_relative(), Commands::optimize(), Commands::optimize_ids(), Scanner::rescan(), UrlMap::build()

### Community 13 - "Stats & Status Counts"
Cohesion: 0.08
Nodes (8): OptimizationLog, JobRunner, StatsResource::CACHE_KEY (omnio_io_stats transient), StatsResource::get(), Commands::stats(), JobRunner::purge_manifests(), JobRunner::purge_rows(), EnvironmentReport::status_counts()

### Community 7 - "REST Controllers & Policies"
Cohesion: 0.06
Nodes (7): AdminPolicy, MediaPolicy, Policy, DiagnosticsController, BulkController, Controller, ScanController

### Community 0 - "Request Validation & Coordinator"
Cohesion: 0.05
Nodes (24): ScanStartRequest, LogQueryRequest, BulkStartRequest, OptimizeRequest, Coordinator, BulkController::start(), BulkController::cancel(), BulkController::run() (+16 more)

### Community 50 - "Stats Resource API"
Cohesion: 0.15
Nodes (7): StatsController, StatsResource, BaseResourceApi, api/ stable data-access layer, Commands::restore(), OptimizeController::scan(), BackupManager::disk_usage()

### Community 2 - "Logging & Backup Controller"
Cohesion: 0.06
Nodes (10): LogController, BackupController, Logger, omnio_io_log(), Logger::info(), Logger::warn(), Logger::error(), Logger::mark() (+2 more)

### Community 4 - "CLI Commands & Scanner"
Cohesion: 0.06
Nodes (10): OptimizeController, Commands, Scanner, Commands::diagnostics(), Commands::requeue(), Commands::rescan(), MediaLibraryHandler::column_state(), MediaLibraryHandler::reason_label() (+2 more)

### Community 35 - "Scan Scheduling & Cancel"
Cohesion: 0.11
Nodes (5): ScanJobRunner, Coordinator::cancel(), Runner::cancel(), ScanRunner::cancel(), Scan frequencies (manual/daily/weekly/monthly)

### Community 46 - "MIME Repair"
Cohesion: 0.13
Nodes (11): MimeReconciler, Commands::repair_backups(), Commands::repair_mime(), repair-mime then repair-backups then purge ordering, BackupController::MAX_BATCHES (500), BackupController::reconcile(), BackupController::purge(), OptimizeController::reconcileMime() (+3 more)

### Community 3 - "Admin Assets & Notices"
Cohesion: 0.07
Nodes (11): NoticeHandler, ForeignNoticeHandler, AssetHandler, View, get_current_screen(), OIO_Test_Foreign_Notices, EngineFactory::has_engine(), Admin SPA mount view (+3 more)

### Community 48 - "Optimizer Cleanup"
Cohesion: 0.21
Nodes (3): Optimizer, JobRunner::purge(), BackupController::cleanup()

### Community 24 - "Database Rewriter"
Cohesion: 0.15
Nodes (5): DatabaseRewriter, is_serialized(), DatabaseRewriter::process_table(), DatabaseRewriter::replace_value(), DatabaseRewriter::walk()

### Community 67 - "404 Fallback"
Cohesion: 0.36
Nodes (3): Fallback404, Fallback404::maybe_serve_replacement(), Fallback404::lookup()

### Community 19 - "Scan Runner State"
Cohesion: 0.15
Nodes (7): ScanRunner, Coordinator::state(), Runner::state(), ScanRunner::classify(), ScanRunner::state(), ScanRunner::bulk_is_running(), Invariant 22: a status column is not evidence the file exists

### Community 1 - "Conversion Engines"
Cohesion: 0.05
Nodes (11): GdEngine, CwebpEngine, ImagickEngine, convert(), AbstractEngine, EngineInterface, AbstractEngine::exif_orientation(), AbstractEngine::parse_options() (+3 more)

### Community 47 - "Engine Factory Chain"
Cohesion: 0.16
Nodes (7): EngineFactory, EnvironmentReport::engines(), AbstractEngine::supports_file(), EngineFactory::chain(), EngineFactory::get(), EngineFactory::for_file(), EngineFactory::availability()

### Community 21 - "Upload Interceptor"
Cohesion: 0.11
Nodes (6): Interceptor, Logger::start_run(), Interceptor::handle_upload(), Interceptor::handle_sideload(), Interceptor::settle_attached_output(), Interceptor::already_belongs_to_an_attachment()

### Community 9 - "Container & REST Namespace"
Cohesion: 0.09
Nodes (6): Container, Router, REST namespace omnio-image-optimizer/v1, Application::bindCoreComponents(), Container::singleton(), admin Services/config (localized data)

### Community 16 - "Admin Icons"
Cohesion: 0.19
Nodes (13): stroke, IconImage(), IconDisk(), IconBolt(), IconSliders(), IconLayers(), IconGear(), IconArchive() (+5 more)

### Community 77 - "Admin App Mount"
Cohesion: 0.33
Nodes (5): App(), mount, loginAndOpenDashboard(), Library card e2e spec (Unit 12), wp-login smoke spec

### Community 79 - "Masthead & Notices"
Cohesion: 0.40
Nodes (4): Notice(), LogoMark(), Masthead(), EngineNotice()

### Community 17 - "Admin UI Components"
Cohesion: 0.13
Nodes (14): Select(), Spinner(), Range(), FOCUSABLE, Modal(), Toggle(), Button(), NumberInput() (+6 more)

### Community 70 - "Backups Page"
Cohesion: 0.29
Nodes (7): Stat(), CONFIRM_WORD, BackupsPage(), BackupsPage.repair(), BackupsPage.purge(), REST POST backups/reconcile, REST POST backups/purge

### Community 37 - "Settings State & Toasts"
Cohesion: 0.14
Nodes (16): ToastContext, useToast(), ToastProvider(), SettingsPage(), TroubleshootPage(), DiagnosticRow(), omnio_io_settings option, Lifted settings state (+8 more)

### Community 52 - "Library Progress Widgets"
Cohesion: 0.26
Nodes (8): ProgressRing(), Metric(), HeroStats(), ScanSummary(), RunProgress(), formatBytes(), formatTimeAgo(), Scan snapshot (single source of library figures)

### Community 78 - "Card & Section"
Cohesion: 0.60
Nodes (4): Card(), CardHeader(), CardBody(), Section()

### Community 29 - "Bulk Page Client Pumping"
Cohesion: 0.13
Nodes (19): BulkPage(), DryRunPanel(), LibraryCard(), Shared scan snapshot state, BulkPage.absorb(), BulkPage.pumpScan(), BulkPage.pumpBulk(), BulkPage.startScan() (+11 more)

### Community 58 - "Block Editor Panel"
Cohesion: 0.33
Nodes (9): formatBytes(), STATUS_META, Panel(), request(), fetchStatus(), optimize(), restore(), TARGET_BLOCKS (+1 more)

### Community 36 - "Media Modal Panel"
Cohesion: 0.20
Nodes (14): request(), formatDuration(), formatFormat(), el(), logoMark(), STATUS_META, buildPanel(), renderPanel() (+6 more)

### Community 81 - "CLI Bulk Harness"
Cohesion: 0.40
Nodes (4): cli_binaries(), cli_run(), cli_says(), cli_status_count()

### Community 18 - "PHP Test Harness"
Cohesion: 0.13
Nodes (15): Harness, harness_require_site(), harness_scan_to_completion(), harness_set_baseline_settings(), harness_engine_names(), harness_make_converter(), harness_count_log_rows(), harness_import_attachment() (+7 more)

### Community 74 - "Library Scan e2e"
Cohesion: 0.29
Nodes (5): { test, expect }, consoleErrors, ring, scanButton, frequency

### Community 55 - "Bulk Runner Internals"
Cohesion: 0.18
Nodes (12): Runner::process_batch(), Runner::dry_run(), Runner::flush_pending_rewrite(), Runner::next_batch_size(), Runner::is_resumable(), omnio_io_bulk_lock, Scanner::mime_types(), Scanner::count_pending() (+4 more)

### Community 54 - "Bulk Job Cron"
Cohesion: 0.20
Nodes (11): Runner::PROGRESS_OPTION (omnio_io_bulk_progress), Uninstall routine, Backups preserved on uninstall, BulkJobRunner::HOOK (omnio_io_bulk_batch), BulkJobRunner::register(), BulkJobRunner::run(), BulkJobRunner::schedule_next(), BulkJobRunner::has_active_run() (+3 more)

### Community 42 - "Settings-Driven Runs"
Cohesion: 0.18
Nodes (15): ScanRunner::start(), ScanRunner::is_running(), StoreSettings::all(), StoreSettings::get(), ActivationHandler::handle(), ActivationHandler::handleNetwork(), ActivationHandler::activateSite(), ScanJobRunner::SCHEDULE_HOOK (omnio_io_scheduled_scan) (+7 more)

### Community 66 - "Scan Completion"
Cohesion: 0.25
Nodes (8): ScanRunner::process_batch(), ScanJobRunner::schedule_next(), Coordinator::on_scan_completed(), Runner::start(), ScanRunner::finish(), omnio_io_library_scan snapshot option, Scanner::summary(), EnvironmentReport::plugin()

### Community 38 - "App Facade Accessors"
Cohesion: 0.14
Nodes (15): ScanRunner::snapshot(), App::getInstance(), App::make(), App::view(), App::router(), MediaLibraryHandler::render_column(), MediaLibraryHandler::handle_bulk_action(), MediaLibraryHandler::notice() (+7 more)

### Community 65 - "Boot Config & Bindings"
Cohesion: 0.22
Nodes (9): boot/bindings.php container bindings, config/app.php (app config), config/optimizer.php (optimizer defaults and bounds), Application::__construct() boot sequence, Application::loadConfig(), Application::loadBindings(), Application::requireCommonFiles() (hook manifests), app/Hooks/filters.php (+1 more)

### Community 60 - "Settings Registration"
Cohesion: 0.22
Nodes (10): StoreSettings::OPTION (omnio_io_settings), StoreSettings::defaults(), StoreSettings::register(), StoreSettings::sanitize(), actions.php registration manifest, is_admin() gate on init priority 5, JobRunner::HOOK (omnio_io_purge_backups), JobRunner::register() (+2 more)

### Community 80 - "Deactivation Unschedule"
Cohesion: 0.40
Nodes (5): DeactivationHandler::handle(), JobRunner::unschedule(), ScanJobRunner::unschedule_batch(), ScanJobRunner::unschedule_recurring(), ScanJobRunner::unschedule()

### Community 53 - "Foreign Notice Stripping"
Cohesion: 0.20
Nodes (11): ForeignNoticeHandler::register(), ForeignNoticeHandler::strip_foreign_notices(), ForeignNoticeHandler::is_plugin_screen(), ForeignNoticeHandler::strip_hook(), ForeignNoticeHandler::is_own_callback(), omnio_io_hide_foreign_notices filter, MenuHandler::SLUG (omnio-image-optimizer), MenuHandler::missing_build_notice() (+3 more)

### Community 56 - "Optimize/Restore Flow"
Cohesion: 0.18
Nodes (7): OptimizeController::optimize(), OptimizeController::restore(), AttachmentConverter::convert(), AttachmentConverter::restore(), Optimizer::optimize(), Optimizer::can_optimize(), BackupManager::delete()

### Community 6 - "Architecture Conventions"
Cohesion: 0.07
Nodes (33): Agent Instructions — Omnio Image Optimizer, Composer PSR-4 autoload (no runtime dependencies), FluentCart-style layout, app/Hooks/actions.php registration manifest, app/Http/Routes/api.php fluent route DSL, Plugin rewrites and deletes user media, Research the graph, don't read the tree, wordpress-pro skill scoped to WordPress API (+25 more)

### Community 28 - "Architecture Rules"
Cohesion: 0.15
Nodes (20): Never global $wpdb — go through app/Models, Old image URLs served the new file, Architecture, Application, Container, Router, View, App (static facade) (+12 more)

### Community 61 - "Environment Gotchas"
Cohesion: 0.22
Nodes (11): Environment lies: DB socket and CLI vs web PHP, StoreSettings, Working rules, Every Local database is named local, CLI PHP vs web PHP extensions, Standalone PHP harnesses (tests/php/), cli-bulk-e2e.php, harness_require_site() (+3 more)

### Community 20 - "Destructive Path Safety"
Cohesion: 0.11
Nodes (23): tests/php/run.sh rewriter-test, Activity log (capped 10MB), Requeue retryable skips, The destructive path (AttachmentConverter, Rewrite/, Backup/), Scanner::count_pending(), AttachmentConverter, AttachmentConverter::soft_errors() (PERMANENT_SKIPS + RETRYABLE_SKIPS), DatabaseRewriter (+15 more)

### Community 25 - "Product Scope & Features"
Cohesion: 0.10
Nodes (21): Omnio Image Optimizer (readme), Optimize on upload, Individual and bulk optimization of existing library, Dry run, Troubleshoot tab, No external requests / privacy, Skip when WebP would be larger, WP-CLI commands (+13 more)

### Community 31 - "Sideload & MIME Handling"
Cohesion: 0.15
Nodes (19): Backup retention, OptimizationLog, Interceptor, Interceptor::handle_sideload(), Interceptor::already_belongs_to_an_attachment(), MimeReconciler, MediaLibraryHandler, MediaLibraryHandler::optimization_payload() (+11 more)

### Community 40 - "Backup Deletion Incident"
Cohesion: 0.16
Nodes (17): Restore to original, 2026-08-08 glob backup deletion incident, BackupManager, BackupManager::reconcile(), BackupManager::purge_orphans(), BackupManager::manifest_is_intact(), JobRunner, A column describing a file is not evidence the file exists (+9 more)

### Community 68 - "Engine Selection Docs"
Cohesion: 0.32
Nodes (8): Engine detection: Imagick, cwebp, GD, Optimizer, EngineFactory, Engine selection is a chain, not a choice, ImagickEngine, GD engine, Memory estimate before decoding, Future Spec — Before/After Quality Preview

### Community 62 - "Notice Test & Copy"
Cohesion: 0.28
Nodes (9): Issue 2: notice-strip-test missing from web-runner allowlist, UI Context, LibraryCard.jsx, ProgressRing, ConfirmDialog, Copy guidelines (write for site owner), MediaLibraryHandler::reason_label(), ForeignNoticeHandler (+1 more)

### Community 12 - "Bulk Run Orchestration"
Cohesion: 0.12
Nodes (29): Future Spec: Scheduled Background Optimization, Unit 12 One Scan-Backed Dashboard, ScanRunner, Coordinator, ScanJobRunner, BulkJobRunner, ProgressRing, StatsResource (+21 more)

### Community 8 - "Backup Manifest Integrity"
Cohesion: 0.11
Nodes (32): Fixed Issues, OptimizationLog, MimeReconciler, RestFieldHandler, MediaLibraryHandler::optimization_payload(), Interceptor::already_belongs_to_an_attachment(), BackupManager::reconcile(), BackupManager::encode_manifest() (+24 more)

### Community 43 - "Upload Optimization Guards"
Cohesion: 0.20
Nodes (15): Build History (Units), Unit 02 Upload Optimization, GdEngine, Upload Interceptor, Optimizer, manifest_is_intact(), Upload conversion at wp_handle_upload, skipped-larger guard (+7 more)

### Community 15 - "Engine Preference Docs"
Cohesion: 0.12
Nodes (26): Unit 01 Foundation, Unit 10 Hardening + Troubleshoot Tab, EngineFactory, CwebpEngine, ImagickEngine, omnio_io_autoload, RetentionCron, Fallback404 (+18 more)

### Community 26 - "Backup Retention"
Cohesion: 0.17
Nodes (21): Unit 03 Backups & Retention, Unit 13 Backup Purge + Confirm Modal, BackupManager, JobRunner, safe_path(), BackupManager::purge_orphans(), JobRunner::purge_manifests(), Modal.jsx (+13 more)

### Community 44 - "Rewriter Safety Rules"
Cohesion: 0.23
Nodes (15): Unit 04 URL Rewriting, Unit 05 Attachment Conversion, UrlMap, DatabaseRewriter, AttachmentConverter, Mandatory dry run gate, Never string-replace serialized data, strtr longest-key-first replacement (+7 more)

### Community 32 - "Bulk Batch Processing"
Cohesion: 0.15
Nodes (19): Unit 06 Bulk, REST and WP-CLI, Unit 07 React Admin, omnio_io_log table, AttachmentConverter::SOFT_ERRORS, Bulk Scanner, Bulk Runner, Bulk Cli, omnio_io_bulk_progress option (+11 more)

### Community 64 - "Media Modal Buttons"
Cohesion: 0.33
Nodes (9): Unit 08 Media Library UI, OptimizeModeButton, OptimizeSelectedButton, media.js, Rest Controller::optimize(), .media-button skip mechanic, Client-side chunking (3 IDs per call), No React in media modal (+1 more)

### Community 72 - "PHPCS Compliance"
Cohesion: 0.33
Nodes (7): Unit 09 PHPCS Compliance, WordPress Coding Standards (PHPCS), Fix the code, not the sniff, Invariant 12: vendor autoloader-only, Invariant 13: no external HTTP, Ineffective SQL phpcs annotations, I-1 PHPCS never run

### Community 45 - "Admin CSS & Foreign Notices"
Cohesion: 0.24
Nodes (15): Unit 14 Notices and Card Spacing, MenuHandler, ForeignNoticeHandler, NoticeHandler, LibraryCard.jsx, admin.scss, notice-strip-test.php, in_admin_header hook timing (+7 more)

### Community 14 - "Media Grid Bulk Screenshot"
Cohesion: 0.09
Nodes (27): Screenshot: Attachment details optimization panel, Attachment optimization status panel (Image Optimized, Saved 97%), Original vs Optimized size rows (1.57 MB to 49.1 KB), Conversion metadata (Converted to WebP, Engine: imagick, Done in ms), Restore original button, WP core File size shows 49 KB webp filename (post-conversion attachment), Screenshot: Bulk Optimize tab during re-check, Admin tabs: Bulk Optimize / Settings / Backups / Troubleshoot (+19 more)

### Community 49 - "Bulk Tab Screenshot"
Cohesion: 0.21
Nodes (14): Screenshot: Attachment optimization status panel (Restore original highlighted), Attachment optimization status panel (Image Optimized, Saved 97%, original vs optimized size, WebP, engine, timing), Restore original button (red-boxed: styling issue - red text on pale lavender background), Screenshot: Bulk Optimize library card with redundant notices highlighted, Stacked/conflicting notices issue: 'every image already optimized' shown alongside 'previous run stopped after 478 of 479, will resume', Interrupted bulk run resume notice (stale 478 of 479), Screenshot: Bulk Optimize tab full view with skipped-images disclosure expanded, Admin page tabs: Bulk Optimize, Settings, Backups, Troubleshoot (+6 more)

### Community 63 - "Maintenance Screenshot"
Cohesion: 0.28
Nodes (9): Screenshot: Maintenance panel (settings page), Maintenance card: recover images skipped by temporary problems, Stat tiles: Images worth trying again (0) / Leftover temporary files (0), Requeue for another attempt button (disabled at zero), Rescan library against disk button, Clean up temporary files button (disabled at zero), Explainer: site restore leaves DB/uploads out of sync; rescan returns missing results to queue, Optimization queue / retry (+1 more)

### Community 75 - "Attachment Details Screenshots"
Cohesion: 0.47
Nodes (6): Screenshot: Attachment details for WebP image with no optimizer status, Attachment details modal lacks optimization status panel (5L3A0679.webp, 102 KB), Screenshot: Attachment details showing 'Already WebP' status, 'Already WebP' optimization status box in attachment modal, Media Library attachment details modal, Optimization status panel

## Ambiguous Edges - Review These
- `Model` → `Lock`  [AMBIGUOUS]
  app/Services/Lock.php · relation: semantically_similar_to
- `Config` → `config/optimizer.php (optimizer defaults and bounds)`  [AMBIGUOUS]
  config/optimizer.php · relation: semantically_similar_to
- `ProgressRing()` → `HeroStats()`  [AMBIGUOUS]
  resources/admin/Components/ProgressRing.jsx · relation: calls
- `BaseResourceApi` → `Commands::restore()`  [AMBIGUOUS]
  app/Hooks/CLI/Commands.php · relation: references
- `Uninstall routine` → `BulkJobRunner::HOOK (omnio_io_bulk_batch)`  [AMBIGUOUS]
  uninstall.php · relation: references
- `Never delete by glob over a shared directory` → `wp_delete_file() never bare unlink()`  [AMBIGUOUS]
  context/code-standards.md · relation: conceptually_related_to
- `StatsResource` → `UI Context`  [AMBIGUOUS]
  context/ui-context.md · relation: references

## Knowledge Gaps
- **117 isolated node(s):** `{ defineConfig }`, `defaultConfig`, `path`, `name`, `version` (+112 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **19 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **What is the exact relationship between `Model` and `Lock`?**
  _Edge tagged AMBIGUOUS (relation: semantically_similar_to) - confidence is low._
- **What is the exact relationship between `Config` and `config/optimizer.php (optimizer defaults and bounds)`?**
  _Edge tagged AMBIGUOUS (relation: semantically_similar_to) - confidence is low._
- **What is the exact relationship between `ProgressRing()` and `HeroStats()`?**
  _Edge tagged AMBIGUOUS (relation: calls) - confidence is low._
- **What is the exact relationship between `BaseResourceApi` and `Commands::restore()`?**
  _Edge tagged AMBIGUOUS (relation: references) - confidence is low._
- **What is the exact relationship between `Uninstall routine` and `BulkJobRunner::HOOK (omnio_io_bulk_batch)`?**
  _Edge tagged AMBIGUOUS (relation: references) - confidence is low._
- **What is the exact relationship between `Never delete by glob over a shared directory` and `wp_delete_file() never bare unlink()`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **What is the exact relationship between `StatsResource` and `UI Context`?**
  _Edge tagged AMBIGUOUS (relation: references) - confidence is low._