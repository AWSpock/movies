<?php

// require_once(__DIR__ . "/../../models/User_Role.php");

class User_RoleRepository
{
    private $db;
    private $userid;

    private $records = [];
    private $loaded = false;

    public $actionDataMessage;


    public function __construct(DatabaseV2 $db, $userid)
    {
        $this->db = $db;
        $this->userid = $userid;
    }

    public function hasRole($role)
    {
        if (!array_key_exists($role, $this->records)) {
            // a.`id`, a.`created`, a.`updated`, a.`userid`, a.`role`
            $sql = "
                SELECT COUNT(`id`) AS n
                FROM user_role a
                WHERE a.`userid` = ? 
                    AND a.`role` = ?
            ";

            $result = $this->db->query($sql, [
                $this->userid,
                $role,
            ], "is");

            if ($result) {
                $this->records[$role] = $result->fetch_array(MYSQLI_ASSOC)['n'] > 0;
            } else {
                $this->records[$role] = null;
            }
        }
        return $this->records[$role];
    }
}
