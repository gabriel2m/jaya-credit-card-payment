<?php

namespace Tests\Feature\Atoms;

use Symfony\Component\HttpFoundation\Response;

trait TestNeedsValidToken
{
    public function test_needs_valid_token(): void
    {
        $this
            ->postJson(
                uri: route('payments.index'),
                headers: ['Authorization' => 'Bearer not-valid-token']
            )
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
