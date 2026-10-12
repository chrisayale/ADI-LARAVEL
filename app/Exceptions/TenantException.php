<?php

namespace App\Exceptions;

use Exception;

class TenantException extends Exception
{
    public static function saccoNotFound(int $saccoId): self
    {
        return new self("SACCO avec l'ID {$saccoId} introuvable.", 404);
    }
    
    public static function saccoInactive(int $saccoId, string $status): self
    {
        return new self("Le SACCO {$saccoId} est inactif (status: {$status}).", 403);
    }
    
    public static function noSaccoAssigned(): self
    {
        return new self("Aucun SACCO assigné à l'utilisateur.", 403);
    }
    
    public static function onlySuperAdminCanSetContext(): self
    {
        return new self("Seul un Super Admin peut changer le contexte SACCO.", 403);
    }
}
