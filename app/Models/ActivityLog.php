<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Auth;
use App\Core\Database;

final class ActivityLog
{
    public static function write(string $action, ?string $entity = null, ?int $entityId = null): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO activity_log (user_id, action, entity, entity_id, ip) VALUES (:uid, :action, :entity, :eid, :ip)'
        );
        $stmt->execute([
            'uid' => Auth::id(),
            'action' => $action,
            'entity' => $entity,
            'eid' => $entityId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }

    /** @return list<array> */
    public static function recent(int $limit = 15): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT a.*, u.email FROM activity_log a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.id DESC LIMIT :lim'
        );
        $stmt->bindValue('lim', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}