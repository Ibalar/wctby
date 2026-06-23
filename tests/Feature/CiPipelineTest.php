<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CiPipelineTest extends TestCase
{
    #[Test]
    public function ci_workflow_file_exists(): void
    {
        $workflowPath = base_path('.github/workflows/test.yml');
        $this->assertFileExists($workflowPath, 'CI workflow file must exist');
    }

    #[Test]
    public function dockerfile_exists(): void
    {
        $this->assertFileExists(base_path('Dockerfile'));
    }

    #[Test]
    public function docker_compose_exists(): void
    {
        $this->assertFileExists(base_path('docker-compose.yml'));
    }

    #[Test]
    public function env_example_exists(): void
    {
        $this->assertFileExists(base_path('.env.example'));
    }
}
