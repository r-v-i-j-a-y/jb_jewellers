<?php
session_start();

$auth = $_SESSION['auth'] ?? [];

$auth_user_name = $auth['user_name'] ?? null;
$auth_user_id = $auth['id'] ?? null;
$auth_user_email = $auth['email'] ?? null;
$auth_user_mobile = $auth['mobile'] ?? null;
$auth_user_role_id = $auth['role_id'] ?? null;
$auth_user_status = $auth['status'] ?? null;
$auth_user_created_by = $auth['created_by'] ?? null;
$auth_user_updated_by = $auth['updated_by'] ?? null;
$auth_user_created_at = $auth['created_at'] ?? null;