# siren-woocommerce-auto-apply-collaborator-coupon — Agent Instructions

Read this file at the start of every session. It is deliberately thin: it tells
you where the real context lives, not what the context is. Standards, patterns,
and anti-patterns live in the Navigator knowledgebase, not here.

## What This Repo Is

A single-file Siren Affiliates companion WordPress plugin (`plugin.php`) that
automatically applies a collaborator's WooCommerce coupon code when their
referral link triggers an engagement. It hooks `siren_ready` and listens to
Siren's `EngagementsTriggered` event; it has no Composer manifest, no build
step, and no test suite — it deploys as-is alongside Siren Affiliates and
WooCommerce.

- **Initiative:** `siren` (declared in `navigator.yaml`)
- **Repository:** `Novatorius/siren-woocommerce-auto-apply-collaborator-coupon`

## Before Coding: Load the KB Context

Repo standards live in the Navigator KB, referenced from `knowledgeDependencies`
in `navigator.yaml`. They are not in your training data.

1. If `.claude/kb-context.md` exists, read it.
2. If it doesn't, generate it, then read it:

```bash
navigator kb context
```

Do not skip this step. For targeted lookups afterwards:

```bash
navigator kb search "<topic>" --initiative=siren --json
navigator kb show <id>
```

## Verification

There is no `ci` block in `navigator.yaml`: this repo has no setup, test, or
lint commands (single PHP file, no Composer). Verify changes against a
WordPress install running Siren Affiliates and WooCommerce.

What "done" means (PR audit, testing tiers, UAT proof) is defined in the KB:
`global-definition-of-done`. Test-modification rules:
`global-unit-testing-standards-modifying-existing-tests`. Both load via kb context.

## Session End

Journal early and often, not just at the end — journals are the update feed;
discovery docs (Navigator sources) hold current state. Conventions live in the
KB: `journaling-at-novatorius` and `discovery-process`. Quick path: the `/log`
skill, or:

```bash
navigator journal submit "<summary>" "<narrative content>" \
  --initiative=siren --tags=siren-woocommerce-auto-apply-collaborator-coupon --json
```

If this session produced decisions or durable understanding, that is discovery —
capture it per `discovery-process` (journal + charter drafts), don't let it
evaporate into chat history.
