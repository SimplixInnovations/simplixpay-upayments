<?php

namespace Simplixi\SUPCheckout\Tests\Governance;

use PHPUnit\Framework\TestCase;

final class CurrentProjectStateRegressionTest extends TestCase {
    private const T3_MERGED_MAIN_SHA = 'a7a8bbfc3a1dc551127b7ead897c964e95c7cec9';
    private const T2_RUNTIME_BEARING_MAIN_SHA = '047cc86060efb97761d7a0cc4a3806f971ab6fe1';
    private const POST_T3_CERTIFIED_RUNTIME_SHA = '5d8d954ffb8c2e6646dadc4aa1f994b5d56c462e';
    private const POST_T3_CERTIFIED_PACKAGE_SHA256 = '470db11afb2869bc187f0e920aeae4de02e92f6c1f7ca478ef5cb2ac62151b74';

    /**
     * Canonical living records that a fresh chat/agent may use to establish the
     * current Approach 3 coordinate.
     *
     * @return array<int, string>
     */
    private static function living_state_files(): array {
        return array(
            'AGENTS.md',
            'docs/project/START-HERE.md',
            'docs/project/PROJECT-STATUS.md',
            'docs/project/OWNER-HANDOFF.md',
            'docs/project/NEW-CHAT-HANDOFF.md',
            'docs/superpowers/plans/2026-09-10-approach-3-t03-legacy-direct-characterization.md',
            '.ai-architect/implementation-plan.md',
            '.ai-architect/architecture-contract.yaml',
        );
    }

    public function test_living_control_plane_records_the_t3_merged_main_coordinate(): void {
        foreach (self::living_state_files() as $path) {
            $content = self::read_repository_file($path);
            self::assertStringContainsString(
                self::T3_MERGED_MAIN_SHA,
                $content,
                $path . ' must record the current merged T3 main coordinate'
            );
        }
    }

    public function test_start_here_records_post_t3_as_the_current_successor(): void {
        $content = self::read_repository_file('docs/project/START-HERE.md');

        self::assertStringContainsString('post-t3-ecosystem-hardening', $content);
        self::assertStringContainsString('T3', $content);
        self::assertStringNotContainsString('Continue Approach 3 from verified T2', $content);
        self::assertStringNotContainsString('Approach 3 post-T2 evidence review / direct legacy-method characterization', $content);
    }

    public function test_agent_instructions_do_not_send_new_sessions_back_to_pre_t3_work(): void {
        $content = self::read_repository_file('AGENTS.md');

        self::assertStringContainsString('post-t3-ecosystem-hardening', $content);
        self::assertStringNotContainsString(
            'The next substantive action is direct characterization of the legacy return/webhook/private verification surfaces before any further consolidation.',
            $content
        );
    }

    public function test_t3_plan_is_closed_as_verified_runtime_neutral_work(): void {
        $content = self::read_repository_file(
            'docs/superpowers/plans/2026-09-10-approach-3-t03-legacy-direct-characterization.md'
        );

        self::assertStringContainsString('**Status:** DONE / VERIFIED — RUNTIME-NEUTRAL', $content);
        self::assertStringNotContainsString('**Status:** IN PROGRESS', $content);
    }

    public function test_post_t3_plan_distinguishes_merged_t3_from_runtime_bearing_t2(): void {
        $content = self::read_repository_file(
            'docs/superpowers/plans/2026-09-10-post-t3-ecosystem-hardening.md'
        );

        self::assertStringContainsString(self::T3_MERGED_MAIN_SHA, $content);
        self::assertStringContainsString(self::T2_RUNTIME_BEARING_MAIN_SHA, $content);
        self::assertStringNotContainsString(
            'Record T3 merge `a7a8bbfc3a1dc551127b7ead897c964e95c7cec9` as current runtime-bearing main.',
            $content
        );
    }

    public function test_certified_post_t3_runtime_package_evidence_is_exact_in_living_records(): void {
        $expected = array(
            'AGENTS.md' => 'Its deterministic package is 55 files / SHA-256 `' . self::POST_T3_CERTIFIED_PACKAGE_SHA256 . '`.',
            'docs/project/START-HERE.md' => 'Its deterministic package is 55 files / SHA-256 `' . self::POST_T3_CERTIFIED_PACKAGE_SHA256 . '`.',
            'docs/project/PROJECT-STATUS.md' => '- deterministic installable package — **55 files**, SHA-256 `' . self::POST_T3_CERTIFIED_PACKAGE_SHA256 . '`.',
            'docs/project/OWNER-HANDOFF.md' => 'its deterministic 55-file candidate package SHA-256 is `' . self::POST_T3_CERTIFIED_PACKAGE_SHA256 . '`.',
            'docs/project/NEW-CHAT-HANDOFF.md' => '- deterministic package — 55 files / SHA-256 `' . self::POST_T3_CERTIFIED_PACKAGE_SHA256 . '`.',
        );

        foreach ($expected as $path => $package_evidence) {
            $content = self::read_repository_file($path);
            self::assertStringContainsString(self::POST_T3_CERTIFIED_RUNTIME_SHA, $content, $path);
            self::assertStringContainsString($package_evidence, $content, $path);
        }
    }

    public function test_current_post_t3_gate_cannot_regress_before_residual_e2(): void {
        $agents = self::read_repository_file('AGENTS.md');
        $start = self::read_repository_file('docs/project/START-HERE.md');
        $status = self::read_repository_file('docs/project/PROJECT-STATUS.md');
        $handoff = self::read_repository_file('docs/project/NEW-CHAT-HANDOFF.md');
        $implementation = self::read_repository_file('.ai-architect/implementation-plan.md');
        $contract = self::read_repository_file('.ai-architect/architecture-contract.yaml');

        self::assertStringContainsString('R0, R1 and E1 are **DONE / VERIFIED**', $agents);
        self::assertStringContainsString('Current operational gate | **E2 residual ecosystem economics / extension-generated contracts**', $start);
        self::assertStringContainsString('E2 generic/core | **CERTIFIED**', $start);
        self::assertStringContainsString('Current executable gate: **E2 residual ecosystem economics / extension-generated contracts**.', $status);
        self::assertStringContainsString('Current executable gate: **E2 residual ecosystem economics / extension-generated contracts**.', $handoff);
        self::assertStringContainsString('Generic/core E2 exact-head checkpoint: `' . self::POST_T3_CERTIFIED_RUNTIME_SHA . '`', $implementation);
        self::assertStringContainsString('latest_certified_runtime_checkpoint: ' . self::POST_T3_CERTIFIED_RUNTIME_SHA, $contract);
        self::assertStringContainsString('generic_core_e2_status: certified', $contract);
        self::assertStringContainsString('current_gate: e2-residual-ecosystem-economics', $contract);

        self::assertStringNotContainsString('R0 control-plane reconciliation, then remaining R1', $start);
        self::assertStringNotContainsString('Finish/certify **R0**', $handoff);
        self::assertStringNotContainsString('Current gate: **E1 interactive checkout compatibility', $handoff);
    }

    public function test_architecture_contract_records_t3_and_rejects_the_obsolete_t2_next_candidate(): void {
        $content = self::read_repository_file('.ai-architect/architecture-contract.yaml');

        self::assertStringContainsString("  t03:\n", $content);
        self::assertStringContainsString('    status: done_verified', $content);
        self::assertStringContainsString('    merged_main_sha: ' . self::T3_MERGED_MAIN_SHA, $content);
        self::assertStringContainsString('    name: post-t3-ecosystem-hardening', $content);
        self::assertStringNotContainsString('post-t02-evidence-review', $content);
    }

    private static function read_repository_file(string $path): string {
        $root = dirname(__DIR__, 3);
        $full_path = $root . '/' . $path;
        self::assertFileExists($full_path, 'Required living control-plane file is missing: ' . $path);

        $content = file_get_contents($full_path);
        self::assertIsString($content, 'Required living control-plane file is unreadable: ' . $path);

        return $content;
    }
}
