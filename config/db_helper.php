<?php
/**
 * FixNow Database Security & Query Helper
 * Parameterized Prepared Statement Wrapper for MySQLi
 * Supports prepared statements, auto-type inference, flexible argument calling,
 * Bcrypt password hashing with MD5 seamless auto-upgrade, and Haversine distance.
 */

if (!function_exists('db_execute')) {

    /**
     * Infer MySQLi type characters if not explicitly provided
     */
    function _db_infer_types($params) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param) || is_double($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }

    /**
     * Normalize caller arguments to ($conn, $sql, $params, $types)
     */
    function _db_normalize_args($arg1, $arg2, $arg3, $arg4) {
        global $conn;

        // If first argument is mysqli connection
        if (is_object($arg1) && ($arg1 instanceof mysqli)) {
            $c = $arg1;
            $sql = $arg2;
            $params = is_array($arg3) ? $arg3 : [];
            $types = is_string($arg4) ? $arg4 : (is_string($arg3) ? $arg3 : '');
            if (empty($params) && is_array($arg4)) {
                $params = $arg4;
            }
            return [$c, $sql, $params, $types];
        }

        // If first argument is SQL string
        $c = $conn;
        $sql = $arg1;
        $params = [];
        $types = '';

        if (is_array($arg2)) {
            $params = $arg2;
            $types = is_string($arg3) ? $arg3 : '';
        } elseif (is_string($arg2)) {
            $types = $arg2;
            $params = is_array($arg3) ? $arg3 : [];
        }

        return [$c, $sql, $params, $types];
    }

    /**
     * Execute a prepared statement (INSERT, UPDATE, DELETE)
     * Returns ['success' => bool, 'insert_id' => int, 'affected_rows' => int, 'error' => string]
     * Also behaves as boolean in `if (db_execute(...))` if checked via ['success'] or truthy helper.
     */
    function db_execute($arg1, $arg2 = '', $arg3 = [], $arg4 = '') {
        list($c, $sql, $params, $types) = _db_normalize_args($arg1, $arg2, $arg3, $arg4);

        if (!$c) {
            return ['success' => false, 'error' => 'No database connection', 'insert_id' => 0, 'affected_rows' => 0];
        }

        $stmt = $c->prepare($sql);
        if (!$stmt) {
            return ['success' => false, 'error' => $c->error, 'insert_id' => 0, 'affected_rows' => 0];
        }

        if (!empty($params)) {
            if (empty($types)) {
                $types = _db_infer_types($params);
            }
            $stmt->bind_param($types, ...$params);
        }

        $success = $stmt->execute();
        $insert_id = $stmt->insert_id;
        $affected_rows = $stmt->affected_rows;
        $error = $stmt->error;
        $stmt->close();

        return [
            'success' => $success,
            'insert_id' => $insert_id,
            'affected_rows' => $affected_rows,
            'error' => $error
        ];
    }

    /**
     * Execute a prepared query and return a single associative array
     */
    function db_fetch_one($arg1, $arg2 = '', $arg3 = [], $arg4 = '') {
        list($c, $sql, $params, $types) = _db_normalize_args($arg1, $arg2, $arg3, $arg4);

        if (!$c) {
            return null;
        }

        $stmt = $c->prepare($sql);
        if (!$stmt) {
            return null;
        }

        if (!empty($params)) {
            if (empty($types)) {
                $types = _db_infer_types($params);
            }
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    /**
     * Execute a prepared query and return all rows as an array of associative arrays
     */
    function db_fetch_all($arg1, $arg2 = '', $arg3 = [], $arg4 = '') {
        list($c, $sql, $params, $types) = _db_normalize_args($arg1, $arg2, $arg3, $arg4);

        if (!$c) {
            return [];
        }

        $stmt = $c->prepare($sql);
        if (!$stmt) {
            return [];
        }

        if (!empty($params)) {
            if (empty($types)) {
                $types = _db_infer_types($params);
            }
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $stmt->close();
        return $rows;
    }

    /**
     * Securely hash a password using PHP's standard Bcrypt / Argon2
     */
    function secure_password_hash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Securely verify a password against a hash, with seamless legacy MD5 fallback & upgrade
     */
    function secure_password_verify($password, $stored_hash, $conn = null, $update_table = '', $id_col = 'id', $id_val = 0, $pass_col = 'password') {
        global $conn;
        // First check standard modern hash (Bcrypt/Argon2)
        if (password_verify($password, $stored_hash)) {
            return true;
        }

        // Backward compatibility: check if stored password is an older MD5 hash (32 hex characters)
        if (strlen($stored_hash) === 32 && ctype_xdigit($stored_hash)) {
            if (md5($password) === $stored_hash) {
                // Transparently upgrade to Bcrypt if database context is provided
                if ($conn && !empty($update_table) && $id_val > 0) {
                    $new_hash = password_hash($password, PASSWORD_DEFAULT);
                    $upgrade_sql = "UPDATE `$update_table` SET `$pass_col` = ? WHERE `$id_col` = ?";
                    db_execute($conn, $upgrade_sql, [$new_hash, $id_val], "si");
                }
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate Distance between two lat/lng coordinates in Kilometers (Haversine Formula)
     */
    function calculate_distance_km($lat1, $lon1, $lat2, $lon2) {
        if ($lat1 === null || $lon1 === null || $lat2 === null || $lon2 === null) {
            return null;
        }
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 1);
    }
}
