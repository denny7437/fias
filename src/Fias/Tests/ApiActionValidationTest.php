<?php

namespace Fias\Tests;

use Fias\ApiAction\Validation;

class ApiActionValidationTest extends MockDatabaseTest
{
    public function testNotFound()
    {
        $validate = new Validation($this->db, array('address' => 'Непонятный адрес'));
        $result   = $validate->run();

        $this->assertFalse($result['is_valid']);
        $this->assertFalse($result['is_complete']);
    }

    public function testIncomplete()
    {
        $validate = new Validation($this->db, array('address' => 'г москва, ул стахановская'));
        $result   = $validate->run();

        $this->assertFalse($result['is_complete']);
        $this->assertTrue($result['is_valid']);
    }

    public function testValid()
    {
        $validate = new Validation($this->db, array('address' => 'г москва, ул стахановская, 16с17'));
        $result   = $validate->run();

        $this->assertTrue($result['is_complete']);
        $this->assertTrue($result['is_valid']);
    }

    public function testZeroLevel()
    {
        $validate = new Validation($this->db, array('address' => 'г москва'));
        $result   = $validate->run();

        $this->assertFalse($result['is_complete']);
        $this->assertTrue($result['is_valid']);
    }
}