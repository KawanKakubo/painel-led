<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\GovAssaiService;

class GovAssaiServiceTest extends TestCase
{
    private GovAssaiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GovAssaiService();
    }

    public function test_formatar_cpf_with_11_digits(): void
    {
        $result = $this->service->formatarCPF('12345678901');

        $this->assertEquals('123.456.789-01', $result);
    }

    public function test_formatar_cpf_strips_mask_before_formatting(): void
    {
        $result = $this->service->formatarCPF('123.456.789-01');

        $this->assertEquals('123.456.789-01', $result);
    }

    public function test_formatar_cpf_returns_original_when_not_11_digits(): void
    {
        $result = $this->service->formatarCPF('1234');

        $this->assertEquals('1234', $result);
    }

    public function test_formatar_cpf_handles_cpf_with_special_characters(): void
    {
        $result = $this->service->formatarCPF('123-456-789/01');

        $this->assertEquals('123.456.789-01', $result);
    }

    public function test_formatar_cpf_empty_string(): void
    {
        $result = $this->service->formatarCPF('');

        $this->assertEquals('', $result);
    }
}
