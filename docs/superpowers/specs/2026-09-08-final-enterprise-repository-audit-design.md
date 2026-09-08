# SUPCheckout Final Enterprise Repository Audit — Design Specification

**Date:** 2026-09-08
**Status:** Approved design, pending implementation-plan approval gate
**Repository:** `SimplixInnovations/supcheckout`
**Baseline main:** `2f05e72dc96c9bd1dae977e7316023e1a8ea15d9`
**Audit branch / PR:** `hardening/final-enterprise-repository-audit` / PR #77

## 1. Purpose

This closeout prepares SUPCheckout for a completely fresh owner clone and final local acceptance by proving that the repository is clean, coherent, supportable and release-engineered to an enterprise standard.

The audit is repository-wide. It includes runtime code, tests, CI, release tooling, tracked assets, public repository presentation, WordPress.org copy, contribution/security/support policy, living engineering documentation, repository governance and release boundaries.

The goal is not cosmetic perfection through speculative refactoring. The goal is the smallest evidence-backed repository state that is easier to understand, safer to maintain and fully consistent with the already-certified payment compatibility contract.

## 2. Product and compatibility invariants

The following are fixed for this audit:

- Human-facing product name: **SUPCheckout for UPayments**.
- Short product name: **SUPCheckout**.
- Maintainer: **Simplix Innovations**.
- Provider scope: **UPayments only**.
- Canonical repository: `SimplixInnovations/supcheckout`.
- Canonical slug/text domain: `supcheckout`.
- PHP namespace root: `Simplixi\SUPCheckout`.
- New first-party global prefix: `supcheckout_`.
- Constants: `SUPCHECKOUT_*`.
- Package root: `supcheckout/`.
- First-stable physical bootstrap: `supcheckout/UPayments.php`.
- Development version: `0.1.0` unless separately approved.

The word **for** is relationship copy only and must never become part of the repository slug, package slug, text domain, namespace, REST namespace, CSS/JS identity or release artifact.

## 3. Protected compatibility surfaces

This audit must not cosmetically rename or remove payment/provider/persisted identities whose continuity is part of merchant or upgrade compatibility, including:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks and Store API payment identity `upayments`;
- callback identity `wc_upayments`;
- existing `_upay_*` order/user/product/subscription metadata;
- provider identifiers such as `UPayments_order_id`;
- token/provenance/scope/generation state including `upayments_token_identity_secret_v2`;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method identity;
- frozen Phase 9I migration page, nonce, ledger and CLI identities;
- both historical package roots used by upgrade/migration certification;
- public compatibility wrapper `getAPIUrlForRetreiveCards()`;
- the normalized `whitelabled` compatibility shape.

Any proposal touching one of these requires a separately characterized migration defect and a dedicated compatibility contract. That is outside this audit.

## 4. Chosen approach

Use **evidence-first enterprise cleanup**.

Every tracked path and repository control is reviewed, but changes are made only when a concrete reason exists: dead/unreferenced content, stale current-state wording, misleading public presentation, contradictory policy, unsafe residue, duplicated repository-only material, broken references or objectively unnecessary files.

Large or compatibility-sensitive runtime files are not split or rewritten merely to improve aesthetics before the first public release. Existing architecture, payment, H12, security, migration and release regression contracts remain the governing evidence for those surfaces.

This audit intentionally does **not** execute the later “Approach 3” aggressive architecture modernization. That can begin only after this closeout, the fresh owner clone and local acceptance are complete.

## 5. Complete tracked-tree audit

The implementation must inventory the exact recursive Git tree and classify every tracked entry into one of these categories:

1. production PHP/runtime;
2. templates;
3. JavaScript/CSS;
4. runtime images/media;
5. repository presentation assets;
6. release/build scripts;
7. CI/workflows/governance;
8. tests/harnesses/fixtures;
9. public repository documentation;
10. WordPress.org distribution content;
11. living engineering-control documentation;
12. historical evidence/specs/plans;
13. configuration/tooling;
14. intentional compatibility residue.

Every tracked file must end with one explicit outcome:

- **retain as-is**;
- **correct**;
- **remove with evidence**;
- **retain as historical/protected compatibility evidence**.

No file is removed solely because its name looks old.

## 6. Runtime and symbol audit

Review all shipped execution surfaces, including:

- `UPayments.php`;
- `src/**`;
- `includes/**`;
- templates;
- active frontend/admin JavaScript;
- active CSS;
- migration code;
- payment lifecycle and state verification;
- Blocks integration;
- saved-card/token provenance;
- subscriptions;
- HPOS/legacy order storage;
- public order/status handling;
- HTTP transport;
- callbacks/returns;
- activation/uninstall behavior.

Audit for:

- unconditional debug/logging residue;
- `console.log`, `var_dump`, `print_r`, debug-only `error_log`, debugger statements;
- TODO/FIXME/HACK comments that represent unresolved release work;
- dead functions/methods/classes that can be proven unreferenced;
- unsafe dynamic execution;
- direct request access without expected validation/capability/nonce boundaries;
- accidental secret/card/token exposure;
- stale first-party naming that is not protected compatibility state;
- stale or misleading comments;
- duplicate runtime assets or templates;
- broken paths/case sensitivity;
- inconsistent text-domain or namespace use.

A symbol is not removed merely because static search cannot find a call; WordPress hooks, callbacks, reflection/dynamic lookup, templates and provider-driven behavior must be considered first.

## 7. Asset and package integrity

Review every tracked asset under runtime and repository-only asset locations.

For each image, SVG, CSS, JavaScript file or screenshot, prove one of:

- direct code/document reference;
- dynamic path/reference contract;
- WordPress.org/repository presentation role;
- test/evidence role;
- intentional future packaging guard;
- no remaining role.

Runtime wallet/payment images referenced through dynamic `assets/images/<payment-method>.png` construction must be retained even when literal filename search returns no hits.

Repository-only screenshots may be removed only after confirming they have no live README, readme.txt, docs, code, test, release or evidence role.

`.distignore`, the deterministic package manifest and the built ZIP must remain internally consistent. Repository-only source material must never leak into the installable package.

## 8. Public README and repository presentation

The root README is a **product/developer landing page**, not an internal migration diary.

Required reading order:

1. brand mark / product name;
2. concise value proposition;
3. primary engineering-status badges;
4. product/platform metadata badges;
5. quick navigation;
6. product overview and supported boundaries;
7. compatibility;
8. release status;
9. technical identity only where useful to developers;
10. build/development instructions;
11. security/payment integrity;
12. engineering-document map;
13. support;
14. concise service relationship/provenance;
15. license.

The top-level migration/legal admonitions must not appear in the hero area.

The relationship statement should be concise and factual:

> SUPCheckout is developed and maintained by **Simplix Innovations** and integrates WooCommerce with the external **UPayments** payment service. UPayments names and trademarks remain the property of their respective owners.

Detailed lineage/trademark/provenance belongs in `NOTICE.md` and `UPSTREAM.md`.

Current certification run numbers and historical SHAs belong in authoritative engineering evidence documents, not the main README flow, unless a current claim genuinely requires a direct anchor.

## 9. Badge policy

Badges must represent real, useful status rather than decoration.

Primary engineering badges:

- Quality;
- Compatibility;
- Release Artifact;
- Provider Sandbox;
- WordPress.org / Plugin Check;
- CodeQL/security when an accurate stable badge target exists.

Product/platform metadata badges:

- License;
- development version;
- certified WordPress scope;
- certified WooCommerce scope;
- certified PHP scope.

Badge rules:

- every workflow badge links to the actual workflow;
- labels are understandable without internal program knowledge;
- no badge implies UPayments endorsement;
- no badge implies a public release before one exists;
- no badge claims untested broad compatibility;
- first-party/status badge colors should be visually coherent with the Simplix Innovations repository presentation;
- ecosystem badges may use recognizable platform colors when that improves scanning;
- duplicate or low-signal badges are removed.

Existing light/dark Simplix Innovations repository SVGs should be retained if valid and rendered with clean spacing. New brand assets are out of scope unless an existing asset is proven defective.

## 10. WordPress.org `readme.txt`

The WordPress.org readme must be merchant/user focused and consistent with the actual feature boundary.

It must cover:

- concise product description;
- external-service disclosure for UPayments;
- data-sharing explanation appropriate to enabled payment operations;
- installation;
- merchant sandbox validation;
- useful FAQs;
- Classic/Blocks/HPOS certified scope;
- unsupported automatic refunds;
- one-additional-merchant vs arbitrary multi-split boundary;
- subscription auto-deduction qualification boundary;
- WPML/WCML/multicurrency/RTL non-certification unless separately proven;
- privacy/support guidance;
- development-line changelog language that does not pretend a public stable release already exists.

Repeated defensive “not official / no endorsement” wording should be minimized. Necessary provenance/trademark meaning should be expressed once clearly rather than repeated across sections.

## 11. Root policy documents

Review and reconcile:

- `CONTRIBUTING.md`;
- `SECURITY.md`;
- `SUPPORT.md`;
- `MAINTAINERS.md`;
- `NOTICE.md`;
- `UPSTREAM.md`;
- `.github/CODEOWNERS`;
- issue templates;
- pull-request template;
- Dependabot configuration.

They must agree on:

- where bugs belong;
- where security reports belong;
- what must be sanitized;
- what support is community/repository support vs commercial support;
- contribution/review expectations;
- product/provider responsibility;
- source lineage/provenance.

Security-sensitive reports must not direct users to post secrets, credentials, private webhook payloads, tokens or unnecessary personal data publicly.

## 12. Documentation architecture

Living current-authority files must tell one coherent current story:

- `AGENTS.md`;
- `README.md`;
- `docs/COMPATIBILITY.md`;
- `docs/project/PROJECT-STATUS.md`;
- `docs/project/OWNER-HANDOFF.md`;
- `docs/project/NAMING-IDENTITY-STANDARD.md`;
- `docs/project/NEW-CHAT-HANDOFF.md`;
- `docs/project/README.md`;
- `docs/project/RELEASE-ENGINEERING.md` when release mechanics change;
- `CHANGELOG.md`.

Historical records preserve milestone truth, including old product names, SHAs, repository coordinates and then-current gate language. Do not bulk-rebrand or rewrite history to look current.

Living docs must distinguish:

- latest runtime-bearing certified baseline;
- later documentation/presentation-only descendants;
- current live branch/PR state;
- release/publication boundary;
- owner’s next action.

Because the owner intends a completely fresh clone, the final owner handoff must describe a fresh-clone bootstrap rather than normal-working-copy cleanup.

## 13. Repository governance and release engineering

Revalidate:

- default branch;
- branch topology;
- open PRs/issues;
- tags/releases;
- repository metadata/topics;
- active Main Rule/ruleset;
- squash-only policy;
- deletion/non-fast-forward/linear-history protections;
- review-thread resolution;
- strict required checks;
- CODEOWNERS and templates;
- Dependabot;
- pinned workflow actions and least-necessary workflow permissions;
- Composer configuration/lock;
- deterministic release scripts;
- package root and exclusions;
- version/header/readme consistency;
- license/provenance;
- WordPress Plugin Check;
- CodeQL/security workflows.

The audit branch is temporary. After merge and automatic branch deletion, expected persistent topology is `main` only.

No public Git tag, GitHub Release, WordPress.org publication or version promotion is authorized by this audit.

## 14. Testing and evidence strategy

Changes must be evaluated proportionately.

For runtime-impacting fixes, use RED → GREEN executable evidence before accepting the change.

For documentation/presentation-only changes:

- run contradiction/stale-current-state scans;
- verify links/paths and badge targets;
- verify package inclusion/exclusion implications;
- rely on the full repository CI stack before merge.

The final PR head must pass, as applicable:

- Governance;
- Quality Platform;
- H12 Regression Harness;
- full 16-cell Compatibility Certification;
- deterministic Release Artifact;
- packaged legacy and HPOS smoke;
- both historical package-root migration/rollback families;
- Provider Sandbox when triggered/applicable;
- strict packaged WordPress Plugin Check;
- CodeQL/security analysis.

A green older SHA is not sufficient. The exact candidate head is the evidence boundary.

## 15. Review and merge standard

Before merge:

- inspect every changed file;
- prove deleted files are unnecessary;
- scan the diff for accidental payment/provider/persisted identity changes;
- resolve every valid review thread;
- retain zero known Critical or Important finding;
- do not waive a failing gate;
- merge by squash only;
- use an expected-head guard so only the certified SHA can merge.

After merge:

- verify the GitHub-signed main commit;
- require fresh post-merge main checks to settle green;
- verify `main`-only persistent topology;
- verify zero unintended open PRs/issues;
- verify zero tags/releases/publication;
- verify Main Rule integrity;
- capture the final deterministic package SHA-256 and file count.

## 16. Definition of done

This audit is complete only when all of the following are true:

- every tracked path has been reviewed/classified;
- every changed/deleted path has an evidence-backed reason;
- no known dead shipped runtime file remains;
- no known production debug residue remains;
- no known stale living first-party identity remains outside protected contracts;
- no known contradictory current-state documentation remains;
- README/readme/policy copy is concise, accurate and product-first;
- repository presentation is visually coherent and badge claims are real;
- package exclusions and deterministic build remain correct;
- protected UPayments/provider/persisted identities remain intact;
- exact PR head certification is green;
- exact merged main certification is green;
- persistent branch topology is `main` only;
- open PRs/issues are zero unless a real blocker explicitly requires one;
- tags/releases/publication remain absent;
- final owner handoff contains the clean-from-scratch clone procedure and local-agent verification brief.

## 17. Deferred work

The following is intentionally deferred to the later **Approach 3** program:

- decomposition of large compatibility-sensitive runtime classes solely for maintainability;
- broad namespace/class reshaping that requires additional migration analysis;
- removal of compatibility wrappers whose external usage cannot be disproved;
- physical rename of `UPayments.php`;
- new provider abstractions or cross-provider architecture;
- feature expansion;
- public version promotion/publication.

Approach 3 must start from the fully accepted result of this audit, not be mixed into it.
