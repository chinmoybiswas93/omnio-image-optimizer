# Unit 15 — Per-attachment authorization on /optimize and /restore, and a source link for build/

## Goal

Close the two findings from the 2026-10-12 review of the `rename/pixelkiln` branch
(`/security-review` across the whole plugin plus a WordPress.org guideline pass):

1. **I-20 — broken object-level authorization.** `MediaPolicy` checked only `upload_files` and
   `edit_posts`. An **Author** has both but cannot edit other users' attachments, yet could
   `POST /pixelkiln-image-optimizer/v1/optimize` with any IDs: the original is deleted, `post_content`
   in posts they cannot edit is rewritten, and `/restore` undoes an admin's optimization and drops
   the backup. Every other path to the same operations — row actions, bulk actions,
   `canOptimize`/`canRestore` — already checked `edit_post`.
2. **Guideline 4.** The zip ships minified `build/*.js` and excludes `resources/`, with no pointer
   to the source.

## Decisions taken with the user before building

| Question | Decision |
|---|---|
| Where the per-ID check lives | **`MediaPolicy`**. Any ID failing `edit_post` refuses the whole request (403). Not in `AttachmentConverter`, which would put the data-loss path in scope for an auth fix |
| Guideline 4 | **Link the public GitHub repo from `readme.txt`** with build steps. `resources/` stays out of the zip |
| `ForeignNoticeHandler` stripping core notices on our screen | **Leave as is.** Scoped to one screen, filterable, decided in Unit 14 |
| Branch | `fix/rest-object-auth` off `rename/pixelkiln` |

## Files changed

- `app/Http/Policies/MediaPolicy.php` — `edit_post` for every ID, using `OptimizeRequest::sanitizeIds()`
- `readme.txt` — FAQ entry pointing at the source and build commands
- `context/specs/Done/15-rest-object-auth-and-source-link.md`, `context/memory/fixed-issues.md`,
  `context/progress-tracker.md`

No JS change: `resources/editor/actions.js` is the only caller, sends one ID, and surfaces a
refused request's message through `apiFetch`'s rejection.

## Verification

Live on cb-test.local (socket `aRpCXvFUz`, `siteurl` confirmed), with a throwaway Author user and
attachment, both deleted afterwards:

| Case | Before | After |
|---|---|---|
| Author, own image | allowed | allowed |
| Author, admin's image | **allowed** | refused |
| Author, own + admin's image | **allowed** | refused |
| Full dispatch `/optimize`, `/restore`, admin-owned post | **HTTP 200** | HTTP 403 |
| Admin, any image | allowed | allowed |

The full dispatch deliberately used a non-attachment post so the "before" run could not convert
anything. `phpcs` is clean on `MediaPolicy.php`; the 5 errors it reports in
`pixelkiln-image-optimizer.php` predate this unit (rename commit).
