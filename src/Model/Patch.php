<?php

namespace App\Model;

class Patch
{
    private array $buffer;

    public function __construct()
    {
        $this->buffer = [];
    }

    private function clearSql()
    {
        $this->buffer = [];
    }

    /**
     * @param string $sql
     */
    private function addSql(string $sql)
    {
        $this->buffer[] = $sql;
    }

    /**
     * @return array
     */
    public function version_1_0(): array
    {
        $this->clearSql();

        // default account
        $this->addSql('INSERT INTO accounts (name, email, scope, password, type, enabled, created_at, modified_at) 
        VALUES ("admin", "admin@admin.com", "ROLE_ADMIN", "21232f297a57a5a743894a0e4a801fc3", "admin", 1, NOW(), NOW())');

        return $this->buffer;
    }
}
