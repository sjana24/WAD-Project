<?php

interface admin {

    public function add_user(string $username, string $mobileNumber, string $nic);

    public function isExistingUser(string $id);

    public function generate_code(string $nationalId, string $mobile, int $count);

    public function user_status_manage(int $id, string $status);

    public function isExistingCode(string $code);
    
    public function deleteUser(int $id);
}
