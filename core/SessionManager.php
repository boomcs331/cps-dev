<?php

class SessionManager {
    
    public static function checkSession() {
        // ตรวจสอบว่า login แล้วหรือยัง
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: /cps/?url=login');
            exit;
        }
        
        // ตรวจสอบ session หมดอายุ
        if (isset($_SESSION['expires']) && time() > $_SESSION['expires']) {
            session_destroy();
            header('Location: /cps/?url=login');
            exit;
        }
    }
    
    public static function extendSession() {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            $_SESSION['expires'] = time() + (1 * 60 * 60); // ต่ออายุ 1 ชั่วโมง
        }
    }
}