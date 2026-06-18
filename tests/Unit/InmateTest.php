<?php

namespace Tests\Unit;

use App\Models\Inmate;
use PHPUnit\Framework\TestCase;

class InmateTest extends TestCase
{
    public function test_current_room_id_is_cast_to_an_integer(): void
    {
        $inmate = new Inmate;
        $inmate->setRawAttributes(['current_room_id' => '12']);

        $this->assertSame(12, $inmate->current_room_id);
    }
}
